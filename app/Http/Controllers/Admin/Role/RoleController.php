<?php

namespace App\Http\Controllers\Admin\Role;

use App\Components\FlashSession;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * Class for handling everything for the role within the CMS.
 */
class RoleController extends Controller
{

    /** Constant to display the amount in the pagination */
    const PAGINATION_AMOUNT = 8;

    /**
     * Function to display the existing roles.
     */
    public function index()
    {
        // Define the existing roles and add pagination:
        $roles = Role::paginate(self::PAGINATION_AMOUNT);
        // Return the view including the roles:
        return view('admin.role.index', compact('roles'));
    }

    /**
     * Function to create a new role by the filled form-data or display the form.
     */
    public function create()
    {
        // Return the view:
        return view('admin.role.create');
    }
    /**
     * Stores a new created role
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request){

        $this->validate($request, [
            'name' => 'required',
            'authority' => 'required|numeric'
        ]);
        $role = Role::where('name', $request->name)->first();
        // Check if any role is found:
        if ($role) {
            // Display an error:
            FlashSession::addAlert('error', 'Er is al een rol met deze naam');
            // Return to the overview:
            return redirect('/admin/role');
        }
        // Assign form data to the database:
        $role = Role::create([
            'name' => $request->name,
            'authority' => $request->authority
        ]);
        // Check if the creation of the role succeeded:
        if (!$role) {
            // Display an error:
            FlashSession::addAlert('error', 'Het aanmaken van de rol is mislukt');
        } else {
            // Display an message:
            FlashSession::addAlert('success', 'Het aanmaken van de rol is gelukt');
        }
        // Return to the overview:
        return redirect('/admin/role');
    }

    /**
     * Function to display the data of a role.
     * @param $roleId,                      The identifier of a role.
     */
    public function get($id)
    {
        // Define the role:
        $role = Role::all()->where('id', $id)->first();
        // Check if any role is found:
        if(!$role){
            // Display an error:
            FlashSession::addAlert('error', 'Er geen rol gevonden met dit ID');
            // Return to the overview:
            return redirect('/admin/role');
        }
        // Return to view with role data:
        return view('admin.role.get', compact('role'));
    }

    /**
     * Function to edit an existing role.
     * @param $roleId,                      The identifier of a role.
     */
    public function edit($roleId)
    {
        // Return to the view with role data:
        $role = Role::all()->where('id', $roleId)->first();
        if(!$role){
            // Display an error:
            FlashSession::addAlert('error', 'Er geen rol gevonden met dit ID');
            // Return to the overview:
            return redirect('/admin/role');
        }
        return view('admin.role.edit', compact('role'));
    }

    public function update($roleId, Request $request){
        // Define the id of the role:
        $role = Role::all()->where('id', $roleId)->first();
        $this->validate($request, [
            'name' => 'required',
            'authority' => 'required'
        ]);
        $role->name = $request->name;
        $role->authority = $request->authority;
        // Check if edit of the role succeeded:
        if ($role->save()) {
            // Display an error:
            FlashSession::addAlert('success', 'Het aanpassen van de rol is gelukt');
        } else {
            // Display an message:
            FlashSession::addAlert('error', 'Het aanpassen van de rol is mislukt');
        }
        // Redirect to view:
        return redirect('/admin/role');
    }
}
