<?php

namespace App\Http\Controllers;

use App\Helpers\Naija\Naija;
use Illuminate\Http\Request;

class State extends Controller
{
    public function get_lgas_by_state(Request $request)
    {

        // if ($this->form_validation->run() === TRUE) {
        $state_name = $request->state_name;

        if ($state_name == 'Federal Capital Territory') {
            $state_name = 'fct';
        }

        // get the comprehensive information of a state
        $formatted_state = str_replace(' ', '_', strtolower($state_name));

        $state = Naija::state($formatted_state ?? 'fct');

        // get LGAs
        $lgas = $state->getLgas();

        $cities = $state->getAreas();


        if ($lgas || $cities) {
            echo json_encode(['success' => true, 'lgas' => $lgas, 'cities' => $cities]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No LGAs found.']);
        }
    }

    public function get_cities_by_lga(Request $request)
    {
        $lga_id = $request->input('lga_id');
        // $cities = $this->lga_model->get_cities($lga_id); // Fetch cities based on lga_id from your database

        if ($cities) {
            return response()->json(['success' => true, 'cities' => $cities]);
        } else {
            return response()->json(['success' => false, 'message' => 'No Cities found.']);
        }
    }
}
