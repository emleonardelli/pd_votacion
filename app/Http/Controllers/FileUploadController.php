<?php
namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Exception;
use Validator;

class FileUploadController extends Controller
{
    public function fileUpload(Request $r){
        header('Access-Control-Allow-Origin: *');
        $location = '/uploads/certificados';
        $hashName = rand ( 10000 , 99999 );

        $validator = Validator::make($r->all(), [
            'file' => 'required|mimes:png,jpg|max:5120'
        ]);
        if ($validator->fails()) {
            $res = [
                'status' => '200',
                'message' => '',
                'error' => 'Error en el servidor',
                'data' => $validator->errors()
            ];
            return response()->json($res, 500);
        }
        $fileModel = new File();
        $res = '';

        try{
            if($r->file()) {
                $fileName = $hashName.'.'.substr($r->file->getClientOriginalName(), -3);
                $filePath = $r->file('file')->storeAs($location, $fileName, 'public');
                $fileModel->nombre = $fileName;
                $fileModel->path = '/storage/' . $filePath;
                $fileModel->save();
            }
            $res = [
                'status' => '200',
                'message' => 'Archivo cargado exitosamente',
                'certificado_id' => $fileModel->id,
                'error' => ''
            ];
            return response()->json($res, 200);
        } catch (Exception $ex){
            $res = [
                'status' => '500',
                'message' => '',
                'error' => 'Error en el servidor',
                'data' => $ex->getMessage()
            ];
            return response()->json($res, 500);
        }
    }
}