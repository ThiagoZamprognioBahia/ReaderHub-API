<?php

namespace App\Services;

use App\Models\Publisher;
use Illuminate\Support\Facades\DB;

class PublisherService
{
        public function store(array $validatedData)
    {
        $telephone = isset($validatedData['telephone']) ? preg_replace('/[^0-9]/', '', $validatedData['telephone']) : null;
        
        // Check if a record with the same name already exists
        if ($validatedData['name'] && Publisher::where('name', $validatedData['name'])->exists()) {
            return ['error' => 'Já existe uma editora cadastrada com esse nome.'];
        }

        // Check if a record with the same code already exists
        if ($validatedData['code'] && Publisher::where('code', $validatedData['code'])->exists()) {
            return ['error' => 'Já existe uma editora cadastrada com esse código.'];
        }

        // Check if the position 'telephone' exists before making the query
        if ($telephone && Publisher::where('telephone', $telephone)->exists()) {
            return ['error' => 'Já existe uma editora cadastrada com esse telefone.'];
        }

        try {
            DB::beginTransaction();

            $publisher = Publisher::create([
                'name'      => $validatedData['name'],
                'code'      => $validatedData['code'],
                'telephone' => $telephone
            ]);  

            DB::commit();

            return $publisher; 
            
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => 'Erro ao criar editora']; 
        }
    }
    public function update($id, $validatedData)
    {
        $publisher = Publisher::findOrFail($id);

        // Check if a publisher with the given name already exists
        if (isset($validatedData['name'])) {
            $name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['name']);
            $name = ucfirst(mb_strtolower($name, 'UTF-8'));

            if (Publisher::where('name', $name)->where('id', '!=', $id)->exists()) {
                return ['error' => 'Já existe uma editora cadastrada com esse nome.'];
            }
        } else {
            $name = $publisher->name;
        }

        // Check if a publisher already exists with the provided code
        if (isset($validatedData['code'])) {
            if (Publisher::where('code', $validatedData['code'])->where('id', '!=', $id)->exists()) {
                return ['error' => 'Já existe uma editora cadastrada com esse código.'];
            }
            $code = $validatedData['code'];
        } else {
            $code = $publisher->code;
        }

        // Check and format the phone
        if (isset($validatedData['telephone'])) {
            $telephone = preg_replace('/[^0-9]/', '', $validatedData['telephone']);
            if (Publisher::where('telephone', $telephone)->where('id', '!=', $id)->exists()) {
                return ['error' => 'Já existe uma editora cadastrada com esse telefone.'];
            }
        } else {
            $telephone = $publisher->telephone;
        }

        $publisher->update([
            'name'      => $name,
            'code'      => $code,
            'telephone' => $telephone,
        ]);

        return $publisher;
    }
}