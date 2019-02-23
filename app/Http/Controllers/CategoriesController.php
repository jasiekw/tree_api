<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MyRequest;

use App\Category;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }

    private function treeJson($data) 
    {
        
        foreach ($data as $node)
        {
            $info_dane["id"] = $node->get("id");
             $info_dane["name"] = $node->get("name");
             $info_dane["parent"] = $node->get("parent");
             $info_dane["level"] = $node->getLevel();
             $dane[] = $info_dane;
        }
   
        foreach($dane as $key => &$value)
        {
            $output[$value["id"]] = &$value;
        }

        foreach($dane as $key => &$value)
        {
            if($value["parent"] && isset($output[$value["parent"]]))
            {
                $output[$value["parent"]]["nodes"][] = &$value;
            }
   
        }

        foreach($dane as $key => &$value)
        {
            if($value["parent"] && isset($output[$value["parent"]]))
            {
                unset($dane[$key]);
            }
        }
        

        $finalData = [];
        foreach($dane as $item)
        {
            $finalData[] = $item;
        }   
        
        return $finalData;

    }

    public function getNestedTree() 
    {
        $Tree_structures = Category::all();

		foreach($Tree_structures->toArray() as $item) 
		{
			if($item['parent'] == null) 
			{
                $item['parent'] = 0;
            }
            $treeAll[] = $item;
        }

        $tree = new \BlueM\Tree($treeAll);
        $nodes = $tree->getNodes();


        return $this->treeJson($nodes);
    }

    public function getLeveledTree()
    {
        $Tree_structures = Category::all();

		foreach($Tree_structures->toArray() as $item) 
		{
			if($item['parent'] == null) 
			{
                $item['parent'] = 0;
            }
            $treeAll[] = $item;
        }

        $tree = new \BlueM\Tree($treeAll);
        $nodes = $tree->getNodes();

        $tmp = [];
        foreach($nodes as $node) {
            $level = $node->getLevel();
            $arr = $node->toArray();
            $arr['level'] = $level;

            $tmp[] = $arr;
        }

        return $tmp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MyRequest $request)
    {
        return Category::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
        // return Category::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MyRequest $request, $id)
    {
        $category = Category::find($id);

        $result = $category->update($request->all());

        if($result) {
            return response()->json(['status' => 'ok'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $result = $category->delete();
        
        if($result) {
            return response()->json(['status' => 'ok'], 200);
        }
    }
}
