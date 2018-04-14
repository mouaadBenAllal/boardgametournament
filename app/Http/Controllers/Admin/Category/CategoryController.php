<?php

namespace App\Http\Controllers\Admin\Category;

use App\Components\FlashSession;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * Class for handling everything for the category within the CMS.
 */
class CategoryController extends Controller
{

    /** Constant to display the amount in the pagination */
    const PAGINATION_AMOUNT = 8;

    /**
     * Function to display the existing categories.
     */
    public function index()
    {
        // Define the existing categories and add pagination:
        $categories = Category::withTrashed()->paginate(self::PAGINATION_AMOUNT);
        // Return the view including the categories:
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Function to create a new category by the filled form-data or display the form..
     */
    public function create()
    {
        // Return the view:
        return view('admin.category.create');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ]);
            // Define the category:
            $category = Category::where('name', $request->name)->first();
            // Check if any category is found:
            if ($category) {
                // Display an error:
                FlashSession::addAlert('error', 'Er is al een categorie met deze naam');
                // Return to the overview:
                return redirect('/admin/category');
            }
            // Assign form data to the database:
            $category = new Category;
            $category->name = $request->name;
            $category->save();
            // Check if the creation of the category succeeded:
            if (!$category) {
                // Display an error:
                FlashSession::addAlert('error', 'Het aanmaken van de categorie is mislukt');
            } else {
                // Display an success:
                FlashSession::addAlert('success', 'Het aanmaken van de categorie is gelukt');
            }
            // Return to the overview:
            return redirect('/admin/category');
    }


    /**
     * Function to display the data of a category.
     * @param $categoryId,                      The identifier of a category.
     */
    public function get($categoryId = null)
    {
        // Define the id of the category:
        $categoryId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (!is_null($categoryId) ? $categoryId : false));
        // Define the category
        $category = Category::withTrashed()->where('id', $categoryId)->first();
        // Check if any category is found:
        if(!$category){
            // Display an error:
            FlashSession::addAlert('error', 'Er geen categorie gevonden met dit ID');
            // Return to the overview:
            return redirect('/admin/category');
        }
        // Return to view with category data:
        return view('admin.category.get', compact('category'));
    }

    /**
     * Function to edit an existing category.
     * @param $categoryId,                      The identifier of a category.
     */
    public function edit($id){

        // Define the category
        $category = Category::withTrashed()->where('id', $id)->first();
        // Check if any category is found:
        if(!$category){
            // Display an error:
            FlashSession::addAlert('error', 'Er geen categorie gevonden met dit ID');
            // Return to the overview:
            return redirect('/admin/category');
        }
        // Return to the view with category data:
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the information of a category
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id){
        $category = Category::withTrashed()->where('id', $id)->first();
        $this->validate($request, [
            'name' => 'required'
        ]);

        $category->name = $request->name;
        // Check if edit of the category succeeded:
        if ($category->save()) {
            // Display an error:
            FlashSession::addAlert('success', 'Het aanpassen van de categorie is gelukt');
        } else {
            // Display an message:
            FlashSession::addAlert('error', 'Het aanpassen van de categorie is mislukt');
        }
        // Redirect to view:
        return redirect('/admin/category');
    }
}
