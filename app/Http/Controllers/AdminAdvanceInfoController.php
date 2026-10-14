<?php

namespace App\Http\Controllers;

use App\Models\AdvanceInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon; // For date formatting

class AdminAdvanceInfoController extends Controller
{

    private function isJson($string)
    {
        if (!is_string($string)) return false;

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }


    public function index()
    {
        // Get unique titles from the AdvanceInfo table
        // You might want to cache this if you have many records
        $titles = AdvanceInfo::where('admin_status', 1)->select('title')->distinct()->pluck('title')->toArray();

        // Add 'all' as the first tab
        array_unshift($titles, 'all');

        return view('admin.advance_info.index', compact('titles'));
    }

    public function getData(Request $request)
    {
        $draw = $request->input('draw');
        $length = $request->input('length');
        $start = $request->input('start');
        $search = $request->input('search.value');
        $filterTitle = $request->input('title');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');

        $columns = ['id', 'title', 'name', 'label', 'value', 'type', 'options', 'status', 'admin_status', 'created_at'];

        $query = AdvanceInfo::query();
        $query->where('admin_status', 1);
        // Apply global search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Apply tab filter
        if (!empty($filterTitle) && $filterTitle !== 'all') { // Exclude 'all' tab from filtering
            $query->where('title', $filterTitle);
        }

        $totalRecords = AdvanceInfo::where('admin_status', 1)->count(); // Total records without any filtering
        $totalFiltered = $query->count(); // Total records after applying search and tab filter

        // Apply ordering
        if (isset($columns[$orderColumnIndex])) {
            $query->orderBy($columns[$orderColumnIndex], $orderDirection);
        }

        // Apply pagination
        $data = $query->offset($start)
            ->limit($length)
            ->get();

        $result = [];
        foreach ($data as $row) {
            $valueToDisplay = $row->value;
            $optionsToDisplay = $row->options;

            // Process 'value' for display based on 'type'
            if ($this->isJson($row->value)) {
                $decodedValue = json_decode($row->value, true);

                if (is_array($decodedValue)) {
                    if ($row->type === 'json_array' && (!empty($row->options) && $this->isJson($row->options))) {
                        $optionsMap = json_decode($row->options, true);
                        $displayLabels = [];
                        foreach ($decodedValue as $key => $val) {
                            if (($val == 1 || $val === true) && isset($optionsMap[$key])) {
                                $displayLabels[] = $optionsMap[$key];
                            }
                        }
                        $valueToDisplay = implode(', ', $displayLabels);
                    } elseif ($row->type === 'array' || $row->type === 'days') {
                        // For 'array' and 'days' type, if value is stored as JSON array of strings
                        $valueToDisplay = implode(', ', $decodedValue);
                    } else {
                        // Fallback for other JSON types to simply encode for display
                        $valueToDisplay = json_encode($decodedValue);
                    }
                }
            } elseif ($row->type === 'checkbox') {
                $valueToDisplay = ($row->value == 1) ? 'Yes' : 'No';
            }

            // Process 'options' for display
            if ($this->isJson($row->options)) {
                $decodedOptions = json_decode($row->options, true);
                if (is_array($decodedOptions)) {
                    // Convert associative array to a readable string
                    $tempOptions = [];
                    foreach ($decodedOptions as $key => $val) {
                        // Check if key is numeric, if so, it's likely a simple array of values
                        $tempOptions[] = is_numeric($key) ? $val : "$key: $val";
                    }
                    $optionsToDisplay = implode(', ', $tempOptions);

                    // Truncate for display if too long
                    if (strlen($optionsToDisplay) > 50) {
                        $optionsToDisplay = substr($optionsToDisplay, 0, 47) . '...';
                    }
                }
            }
            // '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">Delete</button>'

            $result[] = [
                'id' => $row->id,
                'title' => $row->title,
                'name' => $row->name,
                'label' => $row->label,
                'value' => $valueToDisplay, // Use the processed value
                'type' => $row->type,
                'options' => $optionsToDisplay, // Use the processed options
                'status' => $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>',

                'created_at' => Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
                'action' => '<button class="btn btn-sm btn-info btn-edit mb-2 me-1" data-id="' . $row->id . '">Edit</button>'

            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $result
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:advanced_info,name', // Name must be unique
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,number,option,checkbox,days,array,json_array',
            'value' => 'required', // Validation for value depends on type, handled below
            'options' => 'nullable|string', // Options can be empty or a string (JSON/comma-separated)
            'status' => 'required|boolean',

        ];

        // Specific validation for 'value' based on 'type'
        if ($request->input('type') === 'number') {
            $rules['value'] = 'required|numeric';
        } elseif ($request->input('type') === 'checkbox') {
            $rules['value'] = 'required|in:0,1';
        } elseif (in_array($request->input('type'), ['days', 'array', 'json_array'])) {
            $rules['value'] = 'required|array';
            // Further validation for the array content can be added if needed
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Normalize options and value for storage
        if (!empty($data['options']) && $this->isJson($data['options'])) {
            // If options is already a valid JSON string, keep it as is
            // Otherwise, if it's comma-separated, you might want to convert to JSON for consistency
        } elseif (!empty($data['options'])) {
            // If it's not JSON but exists, consider storing it as a comma-separated string
            // Or if you want to force JSON, convert it:
            // $optionsArray = array_map('trim', explode(',', $data['options']));
            // $data['options'] = json_encode(array_combine($optionsArray, $optionsArray)); // Simple key-value
        }

        // The value for 'days', 'array', 'json_array' is expected to be an array from JS
        // We will store it as a JSON string
        if (in_array($request->input('type'), ['days', 'array', 'json_array'])) {
            if (is_array($data['value'])) {
                $data['value'] = json_encode($data['value']);
            } else {
                // This case should ideally not happen if JS handles it correctly
                return response()->json(['success' => false, 'errors' => ['value' => ['Invalid value format for selected type.']]], 422);
            }
        } elseif ($request->input('type') === 'checkbox') {
            $data['value'] = $request->input('value'); // Already '0' or '1'
        }


        $advanceInfo = AdvanceInfo::create($data);

        return response()->json(['success' => true, 'message' => 'Advance Info added successfully!', 'data' => $advanceInfo]);
    }


    public function edit($id)
    {
        $advanceInfo = AdvanceInfo::findOrFail($id);

        // Decode options and value if they are stored as JSON strings
        if ($this->isJson($advanceInfo->options)) {
            $advanceInfo->options = json_decode($advanceInfo->options, true);
        }

        // For the 'value' field, if it's an array or object type and stored as JSON string, decode it
        // The frontend JS will handle re-encoding if changes are made
        if (in_array($advanceInfo->type, ['days', 'array', 'json_array']) && $this->isJson($advanceInfo->value)) {
            $advanceInfo->value = json_decode($advanceInfo->value, true);
        }

        return response()->json($advanceInfo);
    }

    public function update(Request $request, $id)
    {
        $advanceInfo = AdvanceInfo::findOrFail($id);

        $type = $request->input('type'); // define this before use

        $rules = [
            'title'         => 'required|string|max:255',
            'name'          => 'required|string|max:255|unique:advanced_info,name,' . $id,
            // 'label'         => 'required|string|max:255',
            // 'type'          => 'required|string|in:text,number,option,checkbox,days,array,json_array',
            'value'         => 'required',
            // 'options'       => 'nullable|string',
            // 'status'        => 'required|boolean',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Handle options if provided (convert CSV to JSON)
        if (!empty($data['options'])) {
            if (!$this->isJson($data['options'])) {
                $optionsArray = array_map('trim', explode(',', $data['options']));
                $data['options'] = json_encode(array_combine($optionsArray, $optionsArray));
            }
        }

        // Normalize value based on type
        if (in_array($type, ['days', 'array', 'json_array'])) {
            // If it's already an array/object from frontend (e.g. value[] fields)
            if (is_array($data['value']) || is_object($data['value'])) {
                $data['value'] = json_encode($data['value']);
            }
            // If it's JSON string, decode and re-encode for validation
            elseif ($this->isJson($data['value'])) {
                $decoded = json_decode($data['value'], true);
                if (is_array($decoded)) {
                    $data['value'] = json_encode($decoded);
                } else {
                    return response()->json([
                        'success' => false,
                        'errors' => ['value' => ['Value must be a valid JSON array.']]
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => ['value' => ['Invalid value format for selected type.']]
                ], 422);
            }
        }

        // Update the record
        $advanceInfo->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Advance Info updated successfully!',
            'data'    => $advanceInfo
        ]);
    }

    public function destroy($id)
    {
        $advanceInfo = AdvanceInfo::findOrFail($id);
        $advanceInfo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Advance Info deleted successfully!'
        ]);
    }
}
