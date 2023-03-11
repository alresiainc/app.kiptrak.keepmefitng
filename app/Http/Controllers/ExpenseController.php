<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WareHouse;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\Account;
use App\Models\User;

class ExpenseController extends Controller
{
    public function allExpense ()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $expenses = Expense::where('product_id', null)->get();
        return view('pages.expenses.allExpense', compact('authUser', 'user_role', 'expenses'));
    }

    public function addExpense()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $categories = ExpenseCategory::all();
        $accounts = Account::all();
        $warehouses = WareHouse::all();
        $staffs = User::where('type', 'staff')->get();

        $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        
        return view('pages.expenses.addExpense', compact('authUser', 'user_role', 'categories', 'accounts', 'warehouses', 'account_no', 'staffs'));
    }

    public function addExpensePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'note' => 'required|string',
            'category' => 'required',
            'amount' => 'required',
        ]);
        $expense_code = 'kpe-' . date("Ymd") . '-'. date("his");
        $data = $request->all();

        $expense = new Expense();

        $expense->expense_code = $expense_code;
        $expense->expense_category_id = $data['category'];
        $expense->warehouse_id = !empty($data['warehouse']) ? $data['warehouse'] : null;
        $expense->staff_id = !empty($data['staff_id']) ? $data['staff_id'] : null;
        // $expense->expense_date = $data['expense_date'];
        $expense->amount = $data['amount'];
        // $expense->account_id = $data['account'];
        $expense->note = !empty($data['note']) ? $data['note'] : null;
        
        $expense->created_by = $authUser->id;
        $expense->status = 'true';
        $expense->save();

        return back()->with('success', 'Expense Added Successfully');
    }

    public function addExpenseCategoryPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $category_code = 'kpecat-' . date("Ymd") . '-'. date("his");
        $data = $request->all();
        $category = new ExpenseCategory();
        $category->category_code = $category_code;
        $category->name = $data['name'];
       
        $category->created_by = $authUser->id;
        $category->status = 'true';
        $category->save();

        return back()->with('success', 'Expense Category Added Successfully');
    }

    //ajax
    public function addExpenseCategoryAjaxPost(Request $request)
    {
        $authUser = auth()->user();
        //$user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $category_code = 'kpecat-' . date("Ymd") . '-'. date("his");
        $data = $request->all();
        $category = new ExpenseCategory();
        $category->category_code = $category_code;
        $category->name = $data['category_name'];
       
        $category->created_by = $authUser->id;
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $expense = Expense::where('unique_key', $unique_key)->first();
        if (!isset($expense)) {
            abort(404);
        }
        $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        $categories = ExpenseCategory::all();
        $accounts = Account::all();
        $warehouses = WareHouse::all();

        $staffs = User::where('type', 'staff')->get();

        return view('pages.expenses.editExpense', compact('authUser', 'user_role', 'expense', 'account_no','categories', 'accounts', 'warehouses', 'staffs'));
    
    }

    public function editExpensePost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
       $data = $request->all();

        $expense = Expense::where('unique_key', $unique_key)->first();
        if (!isset($expense)) {
            abort(404);
        }
        $request->validate([
            'note' => 'required|string',
            'category' => 'required',
            'amount' => 'required',
        ]);

        $expense->expense_category_id = $data['category'];
        $expense->warehouse_id = !empty($data['warehouse']) ? $data['warehouse'] : null;
        $expense->amount = $data['amount'];
        $expense->staff_id = !empty($data['staff_id']) ? $data['staff_id'] : null;
        $expense->note = !empty($data['note']) ? $data['note'] : null;
        
        $expense->created_by = $authUser->id;
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $categories = ExpenseCategory::all();
        return view('pages.expenses.allExpenseCategory', compact('authUser', 'user_role', 'categories'));
    }

    
}
