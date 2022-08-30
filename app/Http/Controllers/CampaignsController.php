<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $campaigns = Campaign::with('group')->get();

            return $campaigns;
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

            $campaign = Campaign::where('name', $request->name)->exists();

            if ($campaign) {
                throw new \Exception('Esta campanha já existe!');
            }

            $createCampaign = new Campaign();

            $createCampaign->name = $name;

            $createCampaign->description = trim($description);

            $createCampaign->save();

            $group = Group::find($request->group_id);
            $group->campaign_id  = $createCampaign->id;
            $group->save();

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
            $campaign = Campaign::with('group')->find($id);

            return $campaign;
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
            $campaign = Campaign::with('group')->find($id);

            if ($request->name) {
                $campaign->name = trim($request->name);
            }

            if ($request->description) {
                $campaign->description = trim($request->description);
            }

            if ($request->group_id) {
                $campaign->group->campaign_id = null;
                $campaign->group->save();

                $addToAnotherGroup = Group::find($request->group_id);
                $addToAnotherGroup->campaign_id = $id;
                $addToAnotherGroup->save();
            }

            $campaign->save();

            DB::commit();
            return response('Campanha alterada com sucesso.', 200);
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
            $campaign = Campaign::with('group')->find($id);
            
            $campaign->group->campaign_id = null;
            $campaign->group->save();

            $campaign->delete();

            DB::commit();
            return response('Campanha deletada com sucesso.', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
}
