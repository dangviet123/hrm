<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'admincp'], function () {

    Route::group(['prefix' => 'home'], function () {
        Route::get('getMenuLeft', 'admincp\home\HomeController@getMenuLeft');
        Route::get('getHeader', 'admincp\home\HomeController@getHeader');
        Route::post('checkLogout', 'admincp\home\HomeController@checkLogout');
    });
    

    Route::post('login/checkLogin','admincp\login\LoginController@checkLogin');

    Route::group(['prefix' => 'bgdata'], function () {
        Route::resource('typemenusystem','admincp\bgdata\TypemenusystemController');
        Route::group(['prefix' => 'typemenusystem'], function () {
            Route::post('activeStatusOne','admincp\bgdata\TypemenusystemController@activeStatusOne');
            Route::post('activeStatusAll','admincp\bgdata\TypemenusystemController@activeStatusAll');
            Route::post('sortBy','admincp\bgdata\TypemenusystemController@sortBy');
            Route::post('checkSlug','admincp\bgdata\TypemenusystemController@checkSlug');
            Route::post('createdMenu','admincp\bgdata\TypemenusystemController@createdMenu');
            Route::post('listmenu','admincp\bgdata\TypemenusystemController@listmenu');
            Route::delete('deleteMenu/{id}', 'admincp\bgdata\TypemenusystemController@deleteMenu');
            Route::post('activeStatusOneMenu','admincp\bgdata\TypemenusystemController@activeStatusOneMenu');
            Route::get('{idMenu}/{idTypeMenu}/editMenu', 'admincp\bgdata\TypemenusystemController@editMenu');
            Route::put('{idMenu}/updateMenu', 'admincp\bgdata\TypemenusystemController@updateMenu');
            Route::post('UpdateOrderByMenu', 'admincp\bgdata\TypemenusystemController@UpdateOrderByMenu');
            Route::get('{idMenu}/loadPermission', 'admincp\bgdata\TypemenusystemController@loadPermission');
            Route::put('{idMenu}/savePermission', 'admincp\bgdata\TypemenusystemController@savePermission');
            Route::get('{id}/exportExcel','admincp\bgdata\TypemenusystemController@exportExcel');
        });

        Route::resource('listpermission','admincp\bgdata\ListpermissionController');
        Route::group(['prefix' => 'listpermission'], function () {
            Route::post('activeStatusOne','admincp\bgdata\ListpermissionController@activeStatusOne');
            Route::post('activeStatusAll','admincp\bgdata\ListpermissionController@activeStatusAll');
            Route::post('sortBy','admincp\bgdata\ListpermissionController@sortBy');
            Route::get('{id}/exportExcel','admincp\bgdata\ListpermissionController@exportExcel');
        });

        Route::resource('grouppermission','admincp\bgdata\GrouppermissionController');
        Route::group(['prefix' => 'grouppermission'], function () {
            Route::post('activeStatusOne','admincp\bgdata\GrouppermissionController@activeStatusOne');
            Route::post('activeStatusAll','admincp\bgdata\GrouppermissionController@activeStatusAll');
            Route::post('sortBy','admincp\bgdata\GrouppermissionController@sortBy');
            Route::get('{idGroup}/permission','admincp\bgdata\GrouppermissionController@permission');
            Route::get('{idMenu}/{idGroup}/loadPermission','admincp\bgdata\GrouppermissionController@loadPermission');
            Route::put('{idMenu}/{idGroup}/savePermission','admincp\bgdata\GrouppermissionController@savePermission');
            Route::put('{idMenu}/{idGroup}/activePermission','admincp\bgdata\GrouppermissionController@activePermission');
            Route::get('{id}/exportExcel','admincp\bgdata\GrouppermissionController@exportExcel');
        });

        Route::resource('provinces','admincp\bgdata\ProvincesController');
        Route::group(['prefix' => 'provinces'], function () {
            Route::post('activeStatusOne','admincp\bgdata\ProvincesController@activeStatusOne');
            Route::post('activeStatusAll','admincp\bgdata\ProvincesController@activeStatusAll');
            Route::post('sortBy','admincp\bgdata\ProvincesController@sortBy');
            Route::get('{id}/exportExcel','admincp\bgdata\ProvincesController@exportExcel');
        });

        Route::resource('district','admincp\bgdata\DistrictController');
        Route::group(['prefix' => 'district'], function () {
            Route::post('activeStatusOne','admincp\bgdata\DistrictController@activeStatusOne');
            Route::post('activeStatusAll','admincp\bgdata\DistrictController@activeStatusAll');
            Route::post('sortBy','admincp\bgdata\DistrictController@sortBy');
            Route::get('{id}/exportExcel','admincp\bgdata\DistrictController@exportExcel');
        });

        Route::resource('ward','admincp\bgdata\WardController');
        Route::group(['prefix' => 'ward'], function () {
            Route::post('activeStatusOne','admincp\bgdata\WardController@activeStatusOne');
            Route::post('activeStatusAll','admincp\bgdata\WardController@activeStatusAll');
            Route::post('sortBy','admincp\bgdata\WardController@sortBy');
            Route::post('loadDistrict','admincp\bgdata\WardController@loadDistrict');
            Route::get('{id}/exportExcel','admincp\bgdata\WardController@exportExcel');
        });
    });

    Route::group(['prefix' => 'staff-information'], function () {
        Route::resource('users','admincp\staffinformation\UsersController');
        Route::group(['prefix' => 'users'], function () {
            Route::post('activeStatusOne','admincp\staffinformation\UsersController@activeStatusOne');
            Route::post('activeStatusAll','admincp\staffinformation\UsersController@activeStatusAll');
            Route::post('checkIDCode','admincp\staffinformation\UsersController@checkIDCode');
            Route::post('checkEmail','admincp\staffinformation\UsersController@checkEmail');
            Route::post('{idUser}/{OrderBy}/deleteListFile','admincp\staffinformation\UsersController@deleteListFile');
            Route::post('{idUser}/deleteImage','admincp\staffinformation\UsersController@deleteImage');
            Route::post('activeSisWorkOne','admincp\staffinformation\UsersController@activeSisWorkOne');
            Route::post('{idUser}/loadingDataUser','admincp\staffinformation\UsersController@loadingDataUser');
            Route::get('{id}/exportExcel','admincp\staffinformation\UsersController@exportExcel');
        });

        Route::resource('achievements','admincp\staffinformation\AchievementsController');
        Route::group(['prefix' => 'achievements'], function () {
            Route::post('activeStatusOne','admincp\staffinformation\AchievementsController@activeStatusOne');
            Route::post('activeStatusAll','admincp\staffinformation\AchievementsController@activeStatusAll');
            Route::post('loadInfoUser','admincp\staffinformation\AchievementsController@loadInfoUser');
            
            Route::post('LockedOne','admincp\staffinformation\AchievementsController@LockedOne');
            Route::post('LockedAll','admincp\staffinformation\AchievementsController@LockedAll');
            Route::post('{idAchievements}/getInfoAchievements','admincp\staffinformation\AchievementsController@getInfoAchievements');
            Route::post('ManageCheckAll','admincp\staffinformation\AchievementsController@ManageCheckAll');
            Route::post('ManageCheckOne','admincp\staffinformation\AchievementsController@ManageCheckOne');
            Route::post('{idAchievements}/{idAchievementsFile}/deleteListFile','admincp\staffinformation\AchievementsController@deleteListFile');
            Route::post('loadListUser','admincp\staffinformation\AchievementsController@loadListUser');
            Route::get('{id}/exportExcel','admincp\staffinformation\AchievementsController@exportExcel');
        });

        Route::resource('promote','admincp\staffinformation\PromoteController');
        Route::group(['prefix' => 'promote'], function () {
            Route::post('activeStatusOne','admincp\staffinformation\PromoteController@activeStatusOne');
            Route::post('activeStatusAll','admincp\staffinformation\PromoteController@activeStatusAll');
            Route::post('loadInfoUser','admincp\staffinformation\PromoteController@loadInfoUser');
            Route::post('ManageCheckOne','admincp\staffinformation\PromoteController@ManageCheckOne');
            Route::post('LockedOne','admincp\staffinformation\PromoteController@LockedOne');
            Route::post('LockedAll','admincp\staffinformation\PromoteController@LockedAll');
            Route::post('{idPromote}/getInfoPromote','admincp\staffinformation\PromoteController@getInfoPromote');
            Route::post('ManageCheckAll','admincp\staffinformation\PromoteController@ManageCheckAll');
            Route::post('{idPromote}/{idPromoteFile}/deleteListFile','admincp\staffinformation\PromoteController@deleteListFile');
            Route::post('loadListUser','admincp\staffinformation\PromoteController@loadListUser');
            Route::get('{id}/exportExcel','admincp\staffinformation\PromoteController@exportExcel');
        });

        Route::resource('departments','admincp\staffinformation\DepartmentsController');
        Route::group(['prefix' => 'departments'], function () {
            Route::post('activeStatusOne','admincp\staffinformation\DepartmentsController@activeStatusOne');
            Route::post('activeStatusAll','admincp\staffinformation\DepartmentsController@activeStatusAll');
            Route::post('sortBy','admincp\staffinformation\DepartmentsController@sortBy');
            Route::get('{id}/exportExcel','admincp\staffinformation\DepartmentsController@exportExcel');
        });

        Route::resource('positions','admincp\staffinformation\PositionsController');
        Route::group(['prefix' => 'positions'], function () {
            Route::post('activeStatusOne','admincp\staffinformation\PositionsController@activeStatusOne');
            Route::post('activeStatusAll','admincp\staffinformation\PositionsController@activeStatusAll');
            Route::post('sortBy','admincp\staffinformation\PositionsController@sortBy');
            Route::get('{id}/exportExcel','admincp\staffinformation\PositionsController@exportExcel');
        });

        Route::resource('levels','admincp\staffinformation\LevelsController');
        Route::group(['prefix' => 'levels'], function () {
            Route::post('activeStatusOne','admincp\staffinformation\LevelsController@activeStatusOne');
            Route::post('activeStatusAll','admincp\staffinformation\LevelsController@activeStatusAll');
            Route::post('sortBy','admincp\staffinformation\LevelsController@sortBy');
            Route::get('{id}/exportExcel','admincp\staffinformation\LevelsController@exportExcel');
        });

        Route::resource('typeofwork','admincp\staffinformation\TypeofWorkController');
        Route::group(['prefix' => 'typeofwork'], function () {
            Route::post('activeStatusOne','admincp\staffinformation\TypeofWorkController@activeStatusOne');
            Route::post('activeStatusAll','admincp\staffinformation\TypeofWorkController@activeStatusAll');
            Route::post('sortBy','admincp\staffinformation\TypeofWorkController@sortBy');
            Route::get('{id}/exportExcel','admincp\staffinformation\TypeofWorkController@exportExcel');
        });

        Route::resource('companys','admincp\staffinformation\CompanysController');
        Route::group(['prefix' => 'companys'], function () {
            Route::post('activeStatusOne','admincp\staffinformation\CompanysController@activeStatusOne');
            Route::post('activeStatusAll','admincp\staffinformation\CompanysController@activeStatusAll');
            Route::post('sortBy','admincp\staffinformation\CompanysController@sortBy');
            Route::post('checkIDCode','admincp\staffinformation\CompanysController@checkIDCode');
            Route::get('{id}/exportExcel','admincp\staffinformation\CompanysController@exportExcel');
        });
    });

    Route::group(['prefix' => 'salarys'], function () {
        Route::resource('calculated','admincp\salarys\CalculatedController');
        Route::group(['prefix' => 'calculated'], function () {
            Route::post('activeStatusOne','admincp\salarys\CalculatedController@activeStatusOne');
            Route::post('activeStatusAll','admincp\salarys\CalculatedController@activeStatusAll');
            Route::post('loadListUser','admincp\salarys\CalculatedController@loadListUser');
            Route::post('loadInfoUser','admincp\salarys\CalculatedController@loadInfoUser');
            Route::post('loadListUserByDepartment','admincp\salarys\CalculatedController@loadListUserByDepartment');
            Route::post('getIDCodeMonthYear','admincp\salarys\CalculatedController@getIDCodeMonthYear');
            Route::post('{idCalculated}/loadingDataDetail','admincp\salarys\CalculatedController@loadingDataDetail');
            Route::post('LockedOne','admincp\salarys\CalculatedController@LockedOne');
            Route::post('LockedAll','admincp\salarys\CalculatedController@LockedAll');
            Route::post('ManageCheckAll','admincp\salarys\CalculatedController@ManageCheckAll');
            Route::post('ManageCheckOne','admincp\salarys\CalculatedController@ManageCheckOne');
            Route::post('{idCalculated}/viewPrint','admincp\salarys\CalculatedController@viewPrint');
            Route::get('{id}/exportExcel','admincp\salarys\CalculatedController@exportExcel'); 
        });
    });
});

