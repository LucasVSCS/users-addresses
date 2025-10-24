<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro por NOME (busca parcial 'like')
        $query->when($request->filled('name'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->input('name') . '%');
        });

        // Filtro por CPF (busca exata)
        $query->when($request->filled('cpf'), function ($q) use ($request) {
            $q->where('cpf', $request->input('cpf'));
        });

        // Filtro por Data: CRIADO DEPOIS DE
        $query->when($request->filled('created_after'), function ($q) use ($request) {
            $q->whereDate('created_at', '>=', $request->input('created_after'));
        });

        // Filtro por Data: CRIADO ANTES DE
        $query->when($request->filled('created_before'), function ($q) use ($request) {
            $q->whereDate('created_at', '<=', $request->input('created_before'));
        });

        $users = $query->paginate(15);

        return $users->toResourceCollection();
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        // Inicia um array para guardar os IDs de todos os endereços que serão associados ao usuário
        $addressIdsToSync = [];

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'cpf' => $validatedData['cpf'],
                'type' => $validatedData['type'] ?? 'user',
            ]);

            // Adiciona os IDs dos endereços existentes (se houver)
            if (!empty($validatedData['existing_addresses'])) {
                $addressIdsToSync = $validatedData['existing_addresses'];
            }

            // Cria novos endereços (se houver) e adiciona seus IDs ao array
            if (!empty($validatedData['new_addresses'])) {
                foreach ($validatedData['new_addresses'] as $addressData) {

                    // Tenta encontrar o endereço, caso existente, ou cria um novo
                    $address = Address::firstOrCreate([
                        'street' => $addressData['street'],
                        'postal_code' => $addressData['postal_code']
                    ]);

                    // Adiciona o ID (do endereço novo ou encontrado) ao array
                    $addressIdsToSync[] = $address->id;
                }
            }

            // Associa todos os endereços ao usuário
            $user->addresses()->sync(array_unique($addressIdsToSync));

            DB::commit();

            $user->load('addresses');

            return (new UserResource($user))
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao criar usuário e associar endereços.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $user = User::with('addresses')
            ->where('external_id', $id)
            ->firstOrFail();

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::where('external_id', $id)->firstOrFail();

        $validatedData = $request->validated();
        $userData = collect($validatedData)->only(['name', 'email', 'cpf', 'type'])->all();

        DB::beginTransaction();
        try {

            // Atualiza o usuário (apenas se houver dados de usuário para atualizar)
            if (!empty($userData)) {
                $user->update($userData);
            }

            // Verifica se dados de endereço foram enviados
            $addressKeysPresent = $request->has('existing_addresses') || $request->has('new_addresses');

            if ($addressKeysPresent) {
                $addressIdsToSync = [];

                // Pega os endereços existentes
                if (!empty($validatedData['existing_addresses'])) {
                    $addressIdsToSync = $validatedData['existing_addresses'];
                }

                // Processa e cria novos endereços
                if (!empty($validatedData['new_addresses'])) {
                    foreach ($validatedData['new_addresses'] as $addressData) {
                        $address = Address::firstOrCreate([
                            'street' => $addressData['street'],
                            'postal_code' => $addressData['postal_code']
                        ]);
                        $addressIdsToSync[] = $address->id;
                    }
                }

                // Remove os endereços antigos e adiciona os novos
                $user->addresses()->sync(array_unique($addressIdsToSync));
            }
            DB::commit();

            // Retorna o usuário atualizado
            $user->load('addresses');
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao atualizar usuário.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(string $id)
    {
        $user = User::where('external_id', $id)->firstOrFail();
        $user->delete();

        return response()->noContent();
    }
}