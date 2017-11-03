<?php

namespace App\Http\Controllers;

use App\Services\ImageFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ImageController extends Controller
{

    public function preview(Request $request)
    {
        $image = $request->file('input');
        if (!$image || !$image->isValid()) {
            return response()->json(['error'=>'invalid input'], 422);
        }
        $filter = $request->input('filter', '');
        if ($filter == 'all') {
            $filter = '';
        }
        $param = $request->input('param', []);
        if ($param) {
            $param = json_decode($param, true);
        }
        $jobId = str_random(32);
        Cache::put($jobId, 1, 180);
        $imgFilter = new ImageFilter($jobId, $image, $filter, $param, true);
        $imgFilter->run();
        return response()->json(['job_id'=>$jobId]);
    }

    public function filter(Request $request)
    {
        $this->validate($request, [
            'job_id'=>'required'
        ]);
        $jobId = $request->input('job_id');
        $filter = $request->input('filter', '');
        if (Cache::has($jobId)) {
            $imgFilter = new ImageFilter($jobId);
            $imgFilter->setFilter($filter);
            $imgFilter->run();
            return response()->json(['job_id'=>$jobId]);
        }
        return response()->json(['error'=>'invalid job_id'], 422);
    }

    public function getResult(Request $request)
    {
        $this->validate($request, [
            'job_id'=>'required'
        ]);
        $jobId = $request->input('job_id');
        $filter = $request->input('filter');
        $thumbnail = $request->input('thumbnail', false);
        if (Cache::has($jobId) && Cache::get($jobId) == 2) {
            $imgFilter = new ImageFilter($jobId);
            $imgFilter->setThumbnail($thumbnail);
            $res = $imgFilter->getResult($filter);
            if ($res) {
                $res = ['images'=>$res];
            }
            return response()->json($res);
        }
        return response()->json(['error'=>'invalid job_id'], 422);
    }

}
