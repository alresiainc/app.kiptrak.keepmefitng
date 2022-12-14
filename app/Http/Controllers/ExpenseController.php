<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WareHouse;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\Account;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allExpense ()
    {
        $expenses = Expense::where('product_id', null)->get();
        return view('pages.expenses.allExpense', compact('expenses'));
    }

    public function addExpense()
    {
        $categories = ExpenseCategory::all();
        $accounts = Account::all();
        $warehouses = WareHouse::all();

        $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        
        return view('pages.expenses.addExpense', compact('categories', 'accounts', 'warehouses', 'account_no'));
    }

    public function addExpensePost(Request $request)
    {
        $expense_code = 'kpe-' . date("Ymd") . '-'. date("his");
        $data = $request->all();

        $expense = new Expense();

        $expense->expense_code = $expense_code;
        $expense->expense_category_id = $data['category'];
        $expense->warehouse_id = $data['warehouse'];
        $expense->expense_date = $data['expense_date'];
        $expense->amount = $data['amount'];
        $expense->account_id = $data['account'];
        $expense->note = !empty($data['note']) ? $data['note'] : null;
        
        $expense->created_by = 1;
        $expense->status = 'true';
        $expense->save();

        return back()->with('success', 'Expense Added Successfully');
    }

    public function addExpenseCategoryPost(Request $request)
    {
        $category_code = 'kpecat-' . date("Ymd") . '-'. date("his");
        $data = $request->all();
        $category = new ExpenseCategory();
        $category->category_code = $category_code;
        $category->name = $data['name'];
       
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        return back()->with('success', 'Expense Category Added Successfully');
    }

    //ajax
    public function addExpenseCategoryAjaxPost(Request $request)
    {
        $category_code = 'kpecat-' . date("Ymd") . '-'. date("his");
        $data = $request->all();
        $category = new ExpenseCategory();
        $category->category_code = $category_code;
        $category->name = $data['category_name'];
       
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();
        //store in array
        $data['category'] = $category;

        // $categories = ExpenseCategory::all();

        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }

    
    public function singleExpense($unique_key)
    {
        //
    }

    public function editExpense($unique_key)
    {
        $expense = Expense::where('unique_key', $unique_key)->first();
        if (!isset($expense)) {
            abort(404);
        }
        $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        $categories = ExpenseCategory::all();
        $accounts = Account::all();
        $warehouses = WareHouse::all();

        return view('pages.expenses.editExpense', compact('expense', 'account_no','categories', 'accounts', 'warehouses'));
    
    }

    public function editExpensePost(Request $request, $unique_key)
    {
       $data = $request->all();

        $expense = Expense::where('unique_key', $unique_key)->first();
        if (!isset($expense)) {
            abort(404);
        }

        $expense->expense_category_id = $data['category'];
        $expense->warehouse_id = $data['warehouse'];
        $expense->expense_date = $data['expense_date'];
        $expense->amount = $data['amount'];
        $expense->account_id = $data['account'];
        $expense->note = !empty($data['note']) ? $data['note'] : null;
        
        $expense->created_by = 1;
        $expense->status = 'true';
        $expense->save();

        return back()->with('success', 'Expense Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function allExpenseCategory()
    {
        $categories = ExpenseCategory::all();
        return view('pages.expenses.allExpenseCategory', compact('categories'));
    }

    
}
