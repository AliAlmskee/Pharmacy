<?php

namespace App\Http\Controllers;

use App\Http\Resources\MedicineResource;
use App\Models\Medicine;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use PDO;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        return MedicineResource::collection(Medicine::paginate());
    }

    public function store(StoreMedicineRequest $request)
    {
        $medicine = Medicine::create($request->validated()) ;

        return new MedicineResource($medicine) ;
    }

    public function show(Medicine $medicine)
    {
        return new MedicineResource($medicine) ;
    }

    public function search(string $name)
    {
       $this->escape_database_uderscore($name);

        $res =  Medicine::where('scientific_name' , 'like' , '%'.$name.'%')
                        ->orWhere('commercial_name' , 'like' , '%'.$name.'%')
                        ->paginate() ;

        if($res->isEmpty()){
            return response()->json([],200) ;
        }

        return MedicineResource::collection($res);
    }
    public function update(UpdateMedicineRequest $request, Medicine $medicine)
    {
        $medicine->update($request->validated());

        return new MedicineResource($medicine) ;
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return response(null , 204);
    }

    private function escape_database_uderscore(string &$name)
    {
         // Check the current database connection
         $connection = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);

         // Escape underscores in the search string
         if ($connection == 'mysql') {
             $name = str_replace('_', '\\_', $name);
         } elseif ($connection == 'sqlite') {
             $name = str_replace('_', '\_', $name); //didn't work with sqlite
         }
    }




            public function search2(Request $request)
        {
            $subname = $request->query('scientific_name');
            if( $request->query('commercial_name'))
            {
                $subname = $request->query('commercial_name');
            }

            if (empty($subname)) {
                return response()->json(['error' => 'Search query is empty']);
            }

            $medicines = Medicine::all();

            foreach ($medicines as $medicine) {
                $name = $medicine->commercial_name;
                $relevanceScore = $this->calculateRelevanceScore($name, $subname);
                $medicine->relevanceScore = $relevanceScore;
            }

            $filteredMedicines = $medicines->filter(function ($medicine) {
                return $medicine->relevanceScore >= 40;
            });

            $sortedMedicines = $filteredMedicines->sortByDesc('relevanceScore');

            $mostRelevantMedicines = $sortedMedicines->take(4)->pluck('commercial_name');

            return response()->json($mostRelevantMedicines);
        }


        function calculateRelevanceScore($name, $subname)
        {
            $name = mb_strtolower($name);
            $subname = mb_strtolower($subname);

            similar_text($name, $subname, $percentage);

            return $percentage;
        }






}
