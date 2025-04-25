<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Type;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $types = Type::all();
        return $this->responseService->response($types, '傳送成功');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        try {
            $request->validate([
                'imgurl' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp,svg|max:20480',
                // 'imgurl' => 'required|file|extensions:jpg,jpeg,png,gif,bmp,webp,svg',
                'name' => 'required|string|max:255',
            ]);
        } catch (ValidationException $e) {
            error_log($e);
            return $this->exceptionsHandler->render($request, $e);
        }


        if ($request->hasFile('imgurl')) {
            try {
                DB::BeginTransaction();

                $path = $request->file('imgurl')->store('uploads', 'public'); // 存到 public/storage/uploads
                $url = asset('api/image-proxy/' . $path); // 回傳可以被前端讀取的 URL


                $post = Type::create([
                    'name' => $request->name,
                    'imgurl' => $url,
                ]);

                DB::commit();

                return $this->responseService->response($post, '上傳成功');
            } catch (\Exception $e) {
                DB::rollBack();
                error_log($e);
                return $this->responseService->response(null, '上傳失敗', false);
            }
            //http://192.168.0.129:8000/storage/uploads/nx6zvIPtan4VLi1WvDpNH6LEdfunr7u7qrJc0roe.svg
        }

        return $this->responseService->response(null, '未上傳圖片', false);
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
    public function update(Request $request, Type $type)
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

            // 有圖片的話
            if ($request->hasFile('imgurl')) {
                // 刪除原本的圖片
                if ($type->imgurl) {
                    // 從 URL 中取出相對路徑
                    $path = Str::after($type->imgurl, 'api/image-proxy/');
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }

                // 上傳新圖片
                $newPath = $request->file('imgurl')->store('uploads', 'public');
                $type->imgurl = asset('api/image-proxy/' . $newPath);
            }

            $data = $request->request->all();

            $type->fill($data);

            $type->save();

            DB::commit();
            return $this->responseService->response($type, '更新成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseService->response(null, '更新失敗', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        //
        try {
            $type->delete();
            return $this->responseService->response($type, '刪除成功');
        } catch (\Exception $e) {
            return $this->responseService->response($type, '刪除失敗', false);
        }
    }
}
