<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\User;

use Illuminate\Http\Request;

class UserHistoryController extends Controller
{
    public function admin(Request $request)
    {
        try {
            $query = History::with([
                'user' => function ($q) {
                    $q->withTrashed();
                }
            ])
                ->withTrashed()
                ->whereHas('user', function ($q) {
                    $q->withTrashed()
                        ->where('roleID', 1);
                })
                ->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($q) use ($search) {
                        $q->withTrashed()
                            ->where('firstName', 'LIKE', "%{$search}%")
                            ->orWhere('lastName', 'LIKE', "%{$search}%")
                            ->orWhere('id', 'LIKE', "{$search}");
                    });

                    $q->orWhere('id', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "{$search}");

                });
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Ensure filters persist across pages
            $histories = $query->paginate(5)->appends($request->query());

            return view('AdminComponents.adminhistory', compact('histories'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load history: ' . $e->getMessage());
        }
    }

    public function operator(Request $request)
    {
        try {
            $query = History::with([
                'user' => function ($q) {
                    $q->withTrashed();
                }
            ])
                ->withTrashed()
                ->whereHas('user', function ($q) {
                    $q->withTrashed()
                        ->where('roleID', 4);
                })
                ->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($q) use ($search) {
                        $q->withTrashed()
                            ->where('firstName', 'LIKE', "%{$search}%")
                            ->orWhere('lastName', 'LIKE', "%{$search}%")
                            ->orWhere('id', 'LIKE', "{$search}");
                    });

                    $q->orWhere('id', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "{$search}");

                });
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Ensure filters persist across pages
            $histories = $query->paginate(5)->appends($request->query());

            return view('AdminComponents.operatorhistory', compact('histories'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load history: ' . $e->getMessage());
        }
    }

    public function teacher(Request $request)
    {
        try {
            $query = History::with([
                'user' => function ($q) {
                    $q->withTrashed();
                }
            ])
                ->withTrashed()
                ->whereHas('user', function ($q) {
                    $q->withTrashed()
                        ->where('roleID', 2);
                })
                ->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($q) use ($search) {
                        $q->withTrashed()
                            ->where('firstName', 'LIKE', "%{$search}%")
                            ->orWhere('lastName', 'LIKE', "%{$search}%")
                            ->orWhere('id', 'LIKE', "{$search}");
                    });

                    $q->orWhere('id', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "{$search}");

                });
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Ensure filters persist across pages
            $histories = $query->paginate(5)->appends($request->query());

            return view('AdminComponents.teacherhistory', compact('histories'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load history: ' . $e->getMessage());
        }
    }

    public function student(Request $request)
    {
        try {
            $query = History::with([
                'user' => function ($q) {
                    $q->withTrashed();
                }
            ])
                ->withTrashed()
                ->whereHas('user', function ($q) {
                    $q->withTrashed()
                        ->where('roleID', 3);
                })
                ->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($q) use ($search) {
                        $q->withTrashed()
                            ->where('firstName', 'LIKE', "%{$search}%")
                            ->orWhere('lastName', 'LIKE', "%{$search}%")
                            ->orWhere('id', 'LIKE', "{$search}");
                    });

                    $q->orWhere('id', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "{$search}");

                });
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Ensure filters persist across pages
            $histories = $query->paginate(5)->appends($request->query());

            return view('AdminComponents.studenthistory', compact('histories'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load history: ' . $e->getMessage());
        }
    }





}
