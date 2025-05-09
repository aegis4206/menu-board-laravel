<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Tab;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TabController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Tab::query();

        if ($request->has('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        $tabs = $query->orderBy('type_id')->orderBy('sort')->get();
        return $this->responseService->response($tabs, '查詢成功');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //


        try {
            DB::BeginTransaction();

            $Tabs = Tab::create($request->all());

            DB::commit();

            return $this->responseService->response($Tabs, '上傳成功');
        } catch (\Exception $e) {
            DB::rollBack();
            error_log($e);
            return $this->responseService->response(null, '上傳失敗', false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tab $tab)
    {
        //
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'imgurl' => 'sometimes|file|mimes:jpg,jpeg,png,gif,bmp,webp,svg|max:20480',
            ]);
        } catch (ValidationException $e) {
            return $this->exceptionsHandler->render($request, $e);
        }

        try {
            DB::beginTransaction();

            $data = $request->request->all();

            $tab->fill($data);

            $tab->save();

            DB::commit();
            return $this->responseService->response($tab, '更新成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseService->response(null, '更新失敗', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tab $tab)
    {
        //
        try {
            $tab->delete();
            return $this->responseService->response($tab, '刪除成功');
        } catch (\Exception $e) {
            return $this->responseService->response($tab, '刪除失敗', false);
        }
    }
}
