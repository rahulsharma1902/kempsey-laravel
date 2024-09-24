<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\Faq;
use App\Models\FaqContent;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function AddFaqCategory(Request $request)
    {
        $rules = [
            'name' => 'required|unique:faq_categories,category_name' . ($request->id ? ',' . $request->id : ''),
            'slug' => 'required|unique:faq_categories,category_slug' . ($request->id ? ',' . $request->id : ''),
        ];
    
        $validatedData = $request->validate($rules);
    
        try {
            if ($request->id) {
                $category = FaqCategory::findOrFail($request->id);
            } else {
                $category = new FaqCategory();
            }
    
            $category->category_name = $validatedData['name'];
            $category->category_slug = $validatedData['slug'];
            $category->save();
    
            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated faq category' : 'Successfully added faq category',
                'data' => $category
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFaqCategory(Request $request,$id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required.'
            ], 400);
        }
    
        $FaqCategory = FaqCategory::find($id);
    
        if (!$FaqCategory) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        }
    
        try {
            $FaqCategory->delete();
            return response()->json([
                'success' => true,
                'data' => 'Category successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the Category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function Faqcategories(Request $request)
    {
        try {
            $faqcategories = FaqCategory::where('status',1)->with('faqs')->get();

            return response()->json([
                'success' => true,
                'data' => $faqcategories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCategoryById(Request $request, $id)
    {
        try {
            $category = FaqCategory::find($id);

            return response()->json([
                'success' => true,
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }


    public function AddFaq(Request $request)
    {
        $rules = [
            'question' => 'required',
            'description' => 'required',
            'category_id' => 'required'
        ];
    
        $validatedData = $request->validate($rules);
    
        try {
            if ($request->id) {
                $faq = Faq::findOrFail($request->id);
            } else {
                $faq = new Faq();
            }
    
            $faq->question = $validatedData['question'];
            $faq->answer = $validatedData['description'];
            $faq->category_id = $validatedData['category_id'];
            $faq->save();
    
            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated faq ' : 'Successfully added faq ',
                'data' => $faq
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFaq(Request $request,$id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Faq ID is required.'
            ], 400);
        }
    
        $Faq = Faq::find($id);
    
        if (!$Faq) {
            return response()->json([
                'success' => false,
                'message' => 'Faq not found.'
            ], 404);
        }
    
        try {
            $Faq->delete();
            return response()->json([
                'success' => true,
                'data' => 'Faq successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the Faq.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function Faqs(Request $request)
    {
        try {
            $Faqs = Faq::where('status',1)->with('category')->get();

            return response()->json([
                'success' => true,
                'data' => $Faqs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFaqId(Request $request, $id)
    {
        try {
            $Faq = Faq::find($id);

            return response()->json([
                'success' => true,
                'data' => $Faq
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }



    public function FaqContent(Request $request){
        try {
            $faqcontent = FaqContent::first();

            return response()->json([
                'success' => true,
                'data' => $faqcontent
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'sub_heading' => 'required',
            'heading' => 'required',
            'content_heading' => 'required',
        ]);

        // return $request->all();
    
        $faqcontent = FaqContent::firstOrNew();
    
        $faqcontent->sub_heading = $request->sub_heading;
        $faqcontent->heading = $request->heading;
        $faqcontent->content_heading = $request->content_heading;
        $faqcontent->banner_image_url = $this->upload($request, 'banner_image_url', $faqcontent->banner_image_url);
       
        $faqcontent->save();
        return response()->json([
            'message' => 'Faq content changed successfully!',
            'data' => $faqcontent
        ]);
    }
    
    public function upload(Request $request, $fieldName, $currentFilePath = null) {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $filename = $fieldName . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('faq_images'), $filename);

            return asset('faq_images/' . $filename);
        }
        return $currentFilePath;
    }
    
}
