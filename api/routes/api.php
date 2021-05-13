<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => 'v1',
    'namespace' => 'V1',
], static function () {
    Route::options('auth', 'AuthController@login');
    Route::post('auth', 'AuthController@login');

    Route::group([
        'middleware' => 'auth',
    ], static function () {
        Route::prefix('auth')->group(static function () {
            Route::get('check', 'AuthController@get');
            Route::get('logout', 'AuthController@logout');
        });

        Route::prefix('users')->group(static function () {
            Route::get('{user?}', 'UsersController@get');
            Route::post('', 'UsersController@post');
            Route::put('{user}', 'UsersController@put');
            Route::delete('{user}', 'UsersController@delete');
        });

        Route::prefix('files')->group(static function () {
            Route::get('list/{folder}', 'FileController@list')->where('folder', '^\d+$');
            Route::post('upload', 'FileController@upload');
            Route::post('make-folder', 'FileController@makeFolder');
            Route::post('cut', 'FileController@cut');
            Route::post('copy', 'FileController@copy');
            Route::patch('set-name/{file}', 'FileController@setName')->where('file', '^\d+$');
            Route::patch('set-meta/{file}', 'FileController@setMeta')->where('file', '^\d+$');
            Route::delete('', 'FileController@delete');

            Route::post('optimize-images', 'FileController@optimizeImages');
            Route::post('optimize-images-revert', 'FileController@optimizeImagesRevert');
        });

        Route::prefix('product')->group(static function () {
            Route::get('{product?}', 'ProductController@get');
            Route::post('', 'ProductController@post');
            Route::put('{product}', 'ProductController@put');
            Route::delete('{product}', 'ProductController@delete');
        });

        Route::prefix('ambiente')->group(static function(){
            Route::get('{ambiente?}','AmbienteController@get');
            Route::post('','AmbienteController@post');
            Route::put('{ambiente}','AmbienteController@put');
            Route::delete('{ambiente}','AmbienteController@delete');
        });

        Route::prefix('blog')->group(static function(){
            Route::get('{blog?}','BlogController@get');
            Route::post('','BlogController@post');
            Route::put('{blog}','BlogController@put');
            Route::delete('{blog}','BlogController@delete');
        });

        Route::prefix('order')->group(static function () {
            Route::get('{order?}', 'OrderController@get');
            Route::post('', 'OrderController@post');
        });

        Route::prefix('categories')->group(static function () {
            Route::get('{categoria?}', 'CategoriesController@get');
            Route::post('', 'CategoriesController@post');
            Route::put('{categoria}', 'CategoriesController@put');
            Route::patch('{categoria}', 'CategoriesController@patch');
            Route::delete('{categoria}', 'CategoriesController@delete');
        });

        Route::prefix('textsBanner')->group(static function () {
            Route::get('{textBanner?}', 'TextBannerController@get');
            Route::post('', 'TextBannerController@post');
            Route::put('{textBanner}', 'TextBannerController@put');
            Route::delete('{textBanner}', 'TextBannerController@delete');
        });

        Route::prefix('seos')->group(static function () {
            Route::get('{seo?}', 'SeoController@get');
            Route::post('', 'SeoController@post');
            Route::put('{seo}', 'SeoController@put');
            Route::delete('{seo}', 'SeoController@delete');
        });

        Route::prefix('plans')->group(static function () {
            Route::get('{plan?}', 'PlanController@get');
            Route::post('', 'PlanController@post');
            Route::put('{plan}', 'PlanController@put');
            Route::delete('{plan}', 'PlanController@delete');
        });

        Route::prefix('services')->group(static function () {
            Route::get('{service?}', 'ServiceController@get');
            Route::post('', 'ServiceController@post');
            Route::put('{service}', 'ServiceController@put');
            Route::delete('{service}', 'ServiceController@delete');
        });

        Route::prefix('covenants')->group(static function () {
            Route::get('{covenant?}', 'CovenantController@get');
            Route::post('', 'CovenantController@post');
            Route::put('{covenant}', 'CovenantController@put');
            Route::delete('{covenant}', 'CovenantController@delete');
        });

        Route::prefix('testimonials')->group(static function () {
            Route::get('{testimonial?}', 'TestimonialController@get');
            Route::post('', 'TestimonialController@post');
            Route::put('{testimonial}', 'TestimonialController@put');
            Route::delete('{testimonial}', 'TestimonialController@delete');
        });

        Route::prefix('teams')->group(static function () {
            Route::get('{team?}', 'TeamController@get');
            Route::post('', 'TeamController@post');
            Route::put('{team}', 'TeamController@put');
            Route::delete('{team}', 'TeamController@delete');
        });

        Route::prefix('instagram')->group(static function () {
            Route::get('{instagram?}', 'InstagramController@get');
            Route::post('', 'InstagramController@post');
            Route::put('{instagram}', 'InstagramController@put');
            Route::delete('{instagram}', 'InstagramController@delete');
        });

        Route::prefix('newsletter')->group(static function () {
            Route::get('{newsletter?}', 'NewsletterController@get');
            Route::post('', 'NewsletterController@post');
        });

        Route::prefix('categoriaAcabament')->group(static function () {
            Route::get('{categoriaAcabamento?}', 'CategoryAcabamentoController@get');
            Route::post('', 'CategoryAcabamentoController@post');
            Route::put('{categoriaAcabamento}', 'CategoryAcabamentoController@put');
            Route::patch('{categoriaAcabamento}', 'CategoryAcabamentoController@patch');
            Route::delete('{categoriaAcabamento}', 'CategoryAcabamentoController@delete');
        });

        Route::prefix('acabament')->group(static function () {
            Route::get('{acabamento?}', 'AcabamentoController@get');
            Route::post('', 'AcabamentoController@post');
            Route::put('{acabamento}', 'AcabamentoController@put');
            Route::patch('{acabamento}', 'AcabamentoController@patch');
            Route::delete('{acabamento}', 'AcabamentoController@delete');
        });

        Route::prefix('categoriaBlog')->group(static function () {
            Route::get('{categoriaBlog?}', 'CategoriaBlogController@get');
            Route::post('', 'CategoriaBlogController@post');
            Route::put('{categoriaBlog}', 'CategoriaBlogController@put');
            Route::patch('{categoriaBlog}', 'CategoriaBlogController@patch');
            Route::delete('{categoriaBlog}', 'CategoriaBlogController@delete');
        });


        Route::prefix('categoriaOption')->group(static function(){
            Route::get('{categoriaOption?}','CategoriaOptionController@get');
            Route::post('','CategoriaOptionController@post');
            Route::put('{categoriaOption}','CategoriaOptionController@put');
            Route::delete('{categoriaOption}','CategoriaOptionController@delete');
        });

        Route::prefix('option')->group(static function(){
            Route::get('{option?}','OptionController@get');
            Route::post('','OptionController@post');
            Route::put('{option}','OptionController@put');
            Route::delete('{option}','OptionController@delete');
        });

        Route::prefix('common-texts')->group(static function () {
            Route::get('{identifier?}', 'CommonTextsController@get');
            Route::patch('', 'CommonTextsController@patch');
        });

        Route::prefix('banners')->group(static function () {
            Route::get('{identifier?}', 'BannersController@get');
            Route::patch('', 'BannersController@patch');
        });


    });

    Route::prefix('public')->group(static function () {
        Route::get('categories/{categoria?}', 'CategoriesController@get');
        Route::get('product/{product?}', 'ProductController@get');
        Route::get('services/{service?}', 'ServiceController@get');
        Route::get('common-texts/{identifier?}', 'CommonTextsController@get');
        Route::get('banners/{identifier?}', 'BannersController@get');
        Route::get('teams/{team?}', 'TeamController@get');
        Route::get('services', 'ServiceController@get');
        Route::get('plans/{plan?}', 'PlanController@get');
        Route::get('testimonial', 'TestimonialController@get');
        Route::post('contact', 'ContactController@send');
        Route::post('newsletter', 'NewsletterController@post');
        Route::post('order', 'OrderController@post');
        Route::get('covenants/{covenant?}', 'CovenantController@get');

        Route::get('productdesta', 'ProductController@getdesta');
        Route::get('productdestc', 'ProductController@getdestc');

        Route::get('tendencia', 'ProductController@gettendencia');

        Route::get('instagram/{instagram?}', 'InstagramController@get');

        Route::get('ambiente/{ambiente?}','AmbienteController@get');
        Route::get('categoriaOption/{categoriaOption?}','CategoriaOptionController@get');

        Route::get('acabamento/{acabamento?}','AcabamentoController@get');
        Route::get('categoriaAcabamento/{categoriaAcabamento?}','CategoryAcabamentoController@get');
        Route::get('categoria_blog/{categoria_blog?}', 'CategoriaBlogController@get');
        Route::get('blog/{blog?}', 'BlogController@get');
        Route::get('group/', 'GroupController@get');
        Route::get('option/{option?}', 'OptionController@get');
        Route::get('order/{order?}', 'OrderController@get');
//        Request que mostra as rotas em retorno na api
//      Route::get('',function(){\Artisan::call('route:list');return \Artisan::output();});
    });

    Route::prefix('storage')->group(static function () {
        Route::get('resources/{permalink}', 'StorageController@resource');
        Route::get('{permalink}/{download?}', 'StorageController@index');
    });
});
