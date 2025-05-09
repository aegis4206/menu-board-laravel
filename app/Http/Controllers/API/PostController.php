<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends BaseController
{
    public function index(Request $request)
    {
        //
        $query = Post::query();
        if ($request->has('type_id')) {
            $query->where('type_id', $request->type_id);
        }
        $posts = $query->orderBy('type_id')->orderBy('tab_id')->orderBy('sort')->get();
        return $this->responseService->response($posts, '查詢成功');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        try {
            $request->validate([
                'imgurl' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp,svg|max:20480',
                'content' => 'required|string',
                'title' => 'required|string|max:255',
                'type_id' => 'required|integer|exists:types,id',
                'tab_id' => 'required|integer|exists:tabs,id',
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


                $post = Post::create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'imgurl' => $url,
                    'type_id' => $request->type_id,
                    'tab_id' => $request->tab_id,
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
    public function show(Post $post)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'type_id' => 'required|integer|exists:types,id',
                'tab_id' => 'required|integer|exists:tabs,id',
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
                if ($post->imgurl) {
                    // 從 URL 中取出相對路徑
                    $path = Str::after($post->imgurl, 'api/image-proxy/');
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }

                // 上傳新圖片
                $newPath = $request->file('imgurl')->store('uploads', 'public');
                $post->imgurl = asset('api/image-proxy/' . $newPath);
            }

            $data = $request->request->all();

            $post->fill($data);

            $post->save();

            DB::commit();
            return $this->responseService->response($post, '更新成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseService->response($post, '更新失敗', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        try {
            $post->delete();
            return $this->responseService->response($post, '刪除成功');
        } catch (\Exception $e) {
            return $this->responseService->response($post, '刪除失敗', false);
        }
    }
}
