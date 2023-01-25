<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Company::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('companies');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {

    }

    /**
     * Upload Logo file. And create Logo path.
     *
     * @param Array
     * @return string
     */
    public function upload($file) {

        $name = preg_replace('/\s+/', '_', $this->parseFileName($file['file']));
        if (Storage::putFileAs('/public/logos/', $file['file'], $name)) {
            return $name;
        }
        return NULL;
    }

    /**
     * Parse file name.
     *
     * @param Array
     * @return string
     */
    private function parseFileName($file)
    {
        $fileName = pathinfo($file->getClientOriginalName())['filename'];
        $extension = pathinfo($file->getClientOriginalName())['extension'];
        return str_replace(['%', '?', '#', '.'], '_', $fileName) . '_' . time() . '.' . $extension;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);
        $data = $request->all();
        if($request->allFiles()){
            $data['logo_path'] = $this->upload($request->allFiles());
        }

        return Company::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Company::find($id);
        return view('company', ['company' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Company::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);
        $data = $request->all();
        $data['logo_path'] = $this->upload($request->allFiles());
        return Company::find($id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return int
     */
    public function destroy($id)
    {
        return Company::destroy($id);
    }
}
