<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AdminResource;
use App\Repositories\AdminRoleRepository;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class AdminRoleController extends APIController
{
    protected $adminRoleRepository;

    public function __construct(AdminRoleRepository $adminRoleRepository)
    {
        $this->adminRoleRepository = $adminRoleRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo "index";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo "create";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo "store";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo "show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo "edit";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $input = $request->only([
            'user_id',
            'roles'
        ]);
        $roles = json_decode($input['roles'], true);
        
        $user = auth()->user();
        
        
        print_r($input['user_id']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        echo "destroy";
    }
}
