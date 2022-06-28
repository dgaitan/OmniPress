<?php

namespace App\Http\Controllers;

use App\Http\Resources\Causes\CauseCollection;
use App\Models\Causes\Cause;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CauseController extends Controller
{
    /**
     * Undocumented function
     *
     * @param  Request  $request
     * @return void
     */
    public function index(Request $request)
    {
        $causes = Cause::orderBy('cause_id');
        $perPage = $request->has('perPage')
            ? $request->input('perPage')
            : 100;

        // Filter by cause type
        if (
            $request->has('cause_type')
            && ! empty($request->input('cause_type'))
            && in_array($request->input('cause_type'), Cause::getValidCauseTypes())
        ) {
            $causes->where('cause_type', $request->input('cause_type'));
        }

        if ($request->has('s') && ! empty($request->input('s'))) {
            $s = $request->input('s');
            $causes->where(function ($query) use ($s) {
                $query->where('name', 'ilike', "%$s%")
                    ->orWhere('id', 'ilike', "%$s%")
                    ->orWhere('cause_id', 'ilike', "%$s%")
                    ->orWhere('beneficiary', 'ilike', "%$s%");
            });
        }

        $causes = $causes->paginate($perPage);
        $data = $this->getPaginationResponse($causes);
        $causes = new CauseCollection($causes);
        $data = array_merge($data, [
            'data' => $causes,
            '_cause_type' => $request->has('cause_type')
                ? $request->input('cause_type')
                : '',
            'cause_types' => Cause::getCauseTypes(),
            '_s' => $request->input('s') ?? '',
        ]);

        return Inertia::render('Causes/Index', $data);
    }
}
