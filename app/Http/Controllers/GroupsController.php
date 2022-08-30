<?php

namespace App\Http\Controllers;

use App\City;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $group = Group::with('city')->get();
            
            return $group;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $description = $request->description ? trim($request->description) : null;
            $description = empty($description) ?: null;
            $name = trim($request->name);

            $group = Group::where('name', $request->name)->exists();

            if ($group) {
                throw new \Exception('Este grupo já existe!');
            }

            $createGroup = new Group;

            $createGroup->name = $name;

            $createGroup->description = $description;

            $createGroup->save();

            $cities = City::whereIn('id', $request->cities)->get();

            foreach ($cities as $city) {
                $city->group_id  = $createGroup->id;
                $city->save();
            }

            DB::commit();
            return response('Grupo criado com sucesso.', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $group = Group::with('cities')->find($id);

            return $group;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $group = Group::with('cities')->find($id);

            if ($request->name) {
                $group->name = trim($request->name);
            }

            if ($request->description) {
                $group->description = trim($request->description);
            }
            
            if ($request->cities) {
                $group->description = trim($request->description);
            }

            $group->save();

            $cities = City::whereIn('id', $request->cities)->get();

            if (!$request->insert) {
                foreach ($cities as $city) {
                    $city->group_id  = null;
                    $city->save();
                }
            } else {
                foreach ($cities as $city) {
                    $city->group_id  = $group->id;
                    $city->save();
                }
            }

            DB::commit();
            return response('Grupo alterado com sucesso.', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $cities = City::where('group_id', $id)->get();

            foreach ($cities as $city) {
                $city->group_id  = null;
                $city->save();
            }

            $group = Group::find($id);

            $group->delete();

            DB::commit();
            return response('Grupo deletado com sucesso.', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
}
