<?php

namespace App\Http\Controllers;

use App\Components\ReportFactory,
    App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Validator;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
        $title = 'Payment app';
        $error = null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $validator = Validator::make($request->all(), [
                'user' => 'required|string',
                'type' => 'required|numeric|min:0',
                'from_date' => 'nullable|date_format:"Y-m-d"',
                'to_date' => 'nullable|date_format:"Y-m-d"',
            ]);

            if ($validator->fails()) {
                $error = 'Need provide user and type';
            } else {
                try {
                    $report = ReportFactory::getReportObject($request->type);

                    return $report->getReport($request->user, $request->from_date, $request->to_date);
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        return view('index', [
            'title' => $title,
            'users' => UserRepository::findAllForReport(),
            'error' => $error,
        ]);
    }
}
