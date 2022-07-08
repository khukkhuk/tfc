<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webpanel as Webpanel;
//====================  ====================
//================  Backend ================
//====================  ====================


Route::get('webpanel/login', [Webpanel\AuthController::class, 'getLogin']);
Route::post('webpanel/login', [Webpanel\AuthController::class, 'postLogin']);
Route::get('webpanel/logout', [Webpanel\AuthController::class, 'logOut']);
Route::get('member/logout', [Webpanel\AuthController::class, 'logOut']);

Route::group(['middleware' => ['Webpanel']], function () {

    Route::prefix('webpanel')->group(function () {
        Route::get('/', [Webpanel\HomeController::class, 'index']);
        Route::post('/ajax/getproduct', [Webpanel\HomeController::class, 'get_product']);
        Route::post('/ajax/getmodel', [Webpanel\HomeController::class, 'get_model']);
        Route::post('/ajax/search_value', [Webpanel\HomeController::class, 'search_value']);
        
        Route::post('/uploadimage_text', [Webpanel\HomeController::class, 'uploadimage_text'])->name('upload');

        Route::prefix('property')->group(function(){
            Route::get('/', [Webpanel\PropertyController::class, 'index']);
            Route::post('/datatable', [Webpanel\PropertyController::class, 'datatable']);
            Route::get('/add', [Webpanel\PropertyController::class, 'add']);
            Route::post('/add', [Webpanel\PropertyController::class, 'insert']);
            Route::get('/edit/{id}',[Webpanel\PropertyController::class, 'edit']);
            Route::post('/edit/{id}',[Webpanel\PropertyController::class, 'update']);
            Route::get('/status/{id}',[Webpanel\PropertyController::class, 'status']);
            Route::get('/destroy/{id}', [Webpanel\PropertyController::class, 'destroy']);
            Route::prefix('view')->group(function(){
                Route::get('/{id}',[Webpanel\PropertyController::class, 'view']);
                Route::get('/{id}/show_modal', [Webpanel\PropertyController::class, 'show_modal']);
                Route::post('/{id}/datatable', [Webpanel\PropertyController::class, 'datatable_modal']);
                Route::post('/{id}/show_modal', [Webpanel\PropertyController::class, 'insert_modal']);
                Route::post('/{id}/changesort', [Webpanel\PropertyController::class, 'changesort_modal']);
                Route::get('/{main}/destroy/{id}', [Webpanel\PropertyController::class, 'destroy_modal']);
            });
        }); 


        Route::prefix('product')->group(function () {
            Route::get('/', [Webpanel\ProductController::class, 'index']);
            Route::post('/datatable', [Webpanel\ProductController::class, 'datatable']);
            Route::get('/add', [Webpanel\ProductController::class, 'add']);
            Route::post('/add', [Webpanel\ProductController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\ProductController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\ProductController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/status/{id}', [Webpanel\ProductController::class, 'status'])->where(['id' => '[0-9]+']);
            Route::post('/changesort', [Webpanel\ProductController::class, 'changesort'])->where(['id' => '[0-9]+']);
            Route::get('/destroy', [Webpanel\ProductController::class, 'destroy']);

            Route::get('/view/{id}', [Webpanel\ProductController::class, 'view'])->where(['id' => '[0-9]+']);
            Route::get('/view/{id}/show_modal', [Webpanel\ProductController::class, 'show_modal'])->where(['id' => '[0-9]+']);
            Route::post('/view/{id}/show_modal', [Webpanel\ProductController::class, 'insert_modal'])->where(['id' => '[0-9]+']);
        });

        Route::prefix('model')->group(function () {
            Route::get('/{id}', [Webpanel\ModelController::class, 'index'])->where(['id' => '[0-9]+']);
            Route::post('/{id}', [Webpanel\ModelController::class, 'update_model'])->where(['id' => '[0-9]+']);
            Route::post('/{id}/datatable', [Webpanel\ModelController::class, 'datatable'])->where(['id' => '[0-9]+']);
            Route::get('/{id}/add', [Webpanel\ModelController::class, 'add'])->where(['id' => '[0-9]+']);
            Route::post('/{id}/add', [Webpanel\ModelController::class, 'insert'])->where(['id' => '[0-9]+']);
            Route::get('/edit/{id}/{sub_id}', [Webpanel\ModelController::class, 'edit'])->where(['id' => '[0-9]+'])->where(['sub_id' => '[0-9]+']);
            Route::post('/edit/{id}/{sub_id}', [Webpanel\ModelController::class, 'update'])->where(['id' => '[0-9]+'])->where(['sub_id' => '[0-9]+']);
            Route::get('/{id}/status/{sub_id}', [Webpanel\ModelController::class, 'status'])->where(['id' => '[0-9]+'])->where(['sub_id' => '[0-9]+']);
            Route::post('/{id}/changesort', [Webpanel\ModelController::class, 'changesort'])->where(['id' => '[0-9]+']);
            Route::get('/{id}/destroy', [Webpanel\ModelController::class, 'destroy'])->where(['id' => '[0-9]+']);

            Route::post('/temp_gallery/{id}', [Webpanel\ModelController::class, 'temp_gallery'])->where(['id' => '[0-9]+']);
            Route::post('/temp_gallery_delete/{id}', [Webpanel\ModelController::class, 'temp_gallery_delete'])->where(['id' => '[0-9]+']);
            Route::get('/gallery/destroy', [Webpanel\ModelController::class, 'gallery_destroy']);
        });

        // Route::prefix('product')->group(function () {
        //     Route::get('/', [Webpanel\ProductController::class, 'index']);
        //     Route::post('/datatable', [Webpanel\ProductController::class, 'datatable']);
        //     Route::get('/add', [Webpanel\ProductController::class, 'add']);
        //     Route::post('/add', [Webpanel\ProductController::class, 'insert']);
        //     Route::get('/edit/{id}', [Webpanel\ProductController::class, 'edit'])->where(['id' => '[0-9]+']);
        //     Route::post('/edit/{id}', [Webpanel\ProductController::class, 'update'])->where(['id' => '[0-9]+']);
        //     Route::get('/status/{id}', [Webpanel\ProductController::class, 'status'])->where(['id' => '[0-9]+']);
        //     Route::post('/changesort', [Webpanel\ProductController::class, 'changesort'])->where(['id' => '[0-9]+']);
        //     Route::get('/destroy', [Webpanel\ProductController::class, 'destroy']);

        //     // Modal
        //     Route::prefix('/edit')->group(function () {
        //         Route::post('/{id}/datatable', [Webpanel\ProductController::class, 'datatable_modal']);
        //         Route::get('/{id}/add', [Webpanel\ProductController::class, 'add_modal'])->where(['id' => '[0-9]+']);
        //         Route::post('/{id}/add', [Webpanel\ProductController::class, 'insert_modal'])->where(['id' => '[0-9]+']);
        //         Route::get('/{id}/editsub/{sub_id}', [Webpanel\ProductController::class, 'edit_modal'])->where(['id' => '[0-9]+'])->where(['sub_id' => '[0-9]+']);
        //         Route::post('/{id}/editsub/{sub_id}', [Webpanel\ProductController::class, 'update_modal'])->where(['id' => '[0-9]+'])->where(['sub_id' => '[0-9]+']);
        //         Route::get('/{id}/status/{sub_id}', [Webpanel\ProductController::class, 'status_modal'])->where(['id' => '[0-9]+'])->where(['sub_id' => '[0-9]+']);
        //         Route::post('/{id}/changesort', [Webpanel\ProductController::class, 'changesort_modal'])->where(['id' => '[0-9]+']);
        //         Route::get('/{id}/destroy', [Webpanel\ProductController::class, 'destroy_modal']);
        //     });
           
        // });

        Route::prefix('test-form')->group(function () {
            Route::get('/', [Webpanel\Test_formController::class, 'index']);
            Route::post('/datatable', [Webpanel\Test_formController::class, 'datatable']);
            Route::get('/add', [Webpanel\Test_formController::class, 'add']);
            Route::post('/add', [Webpanel\Test_formController::class, 'insert']);
            Route::post('/menu/{id}', [Webpanel\Test_formController::class, 'update_active_menu'])->where(['id' => '[0-9]+']);
            Route::get('/{id}', [Webpanel\Test_formController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/{id}', [Webpanel\Test_formController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/status/{id}', [Webpanel\Test_formController::class, 'status'])->where(['id' => '[0-9]+']);
            Route::post('/changesort', [Webpanel\Test_formController::class, 'changesort'])->where(['id' => '[0-9]+']);
            Route::get('/destroy', [Webpanel\Test_formController::class, 'destroy']);
        });

        // System Dev
        Route::prefix('menu')->group(function () {
            Route::get('/', [Webpanel\MenuController::class, 'index']);
            Route::get('/showsubmenu', [Webpanel\MenuController::class, 'showsubmenu']);
            Route::get('/datatable', [Webpanel\MenuController::class, 'datatable']);
            Route::get('/add', [Webpanel\MenuController::class, 'add']);
            Route::post('/add', [Webpanel\MenuController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\MenuController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\MenuController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/icon', [Webpanel\MenuController::class, 'icon']);
            Route::get('/status/{id}', [Webpanel\MenuController::class, 'status'])->where(['id' => '[0-9]+']);
            Route::post('/changesort', [Webpanel\MenuController::class, 'changesort'])->where(['id' => '[0-9]+']);
            Route::post('/changesort_sub', [Webpanel\MenuController::class, 'changesort_sub'])->where(['id' => '[0-9]+']);

            Route::get('/destroy', [Webpanel\MenuController::class, 'destroy']);
            Route::get('/destroy_sub', [Webpanel\MenuController::class, 'destroy_sub']);
        });

        Route::prefix('role')->group(function () {
            Route::get('/', [Webpanel\RoleController::class, 'index']);
            Route::get('/datatable', [Webpanel\RoleController::class, 'datatable']);
            Route::get('/add', [Webpanel\RoleController::class, 'add']);
            Route::post('/add', [Webpanel\RoleController::class, 'insert']);
            Route::post('/menu/{id}', [Webpanel\RoleController::class, 'update_active_menu'])->where(['id' => '[0-9]+']);
            Route::get('/edit/{id}', [Webpanel\RoleController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\RoleController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/status/{id}', [Webpanel\RoleController::class, 'status'])->where(['id' => '[0-9]+']);
            Route::get('/destroy', [Webpanel\RoleController::class, 'destroy']);
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [Webpanel\UserController::class, 'index']);
            Route::post('/datatable', [Webpanel\UserController::class, 'datatable']);
            Route::get('/add', [Webpanel\UserController::class, 'add']);
            Route::post('/add', [Webpanel\UserController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\UserController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\UserController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/status/{id}', [Webpanel\UserController::class, 'status'])->where(['id' => '[0-9]+']);
            Route::get('/destroy', [Webpanel\UserController::class, 'destroy']);
        });
    });
});
?>