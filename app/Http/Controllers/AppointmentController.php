<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Appointment;
use App\Http\Resources\AppointmentsResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAppointment;
use Exception;

class AppointmentController extends Controller
{
    protected $availableTimes = [
                                '09:00',
                                '09:30',
                                '10:00',
                                '10:30',
                                '11:00',
                                '11:30',
                                '12:00',
                                '12:30',
                                '13:00',
                                '13:30',
                                '14:00',
                                '14:30',
                                '15:00',
                                '15:30',
                                '16:00',
                                '16:30',
                                '17:00'];

    public function storeCustomer(Request $request) {
        /**
         * Add a user to the database or retrieve a customers details based upon their email address
         *
         * @param  \Illuminate\Http\Request  $request
         * @return object The newly created user or prexisting user
         */
        $data   = $request->all();
        $name   = $data['name'];
        $email  = $data['email'];
        $phone  = $data['phone'];
        $make   = $data['make'];
        $model  = $data['model'];

        $customer = Customer::firstOrCreate(
            [ 'email_address' => $email ],
            [
                'name'              => $name,
                'phone_number'      => $phone,
                'vehicle_make'      => $make,
                'vehicle_model'     => $model,
            ]
        );

        return $customer;
    }

    public function checkAvailableTimes(Request $request){
        /**
         * Return an array of available date given a post of year, month and date
         *
         * @param  \Illuminate\Http\Request  $request
         * @return array An array of available dates
         */
        $date = $request['date'];
        $date_format = $this->dateFormat($date);

        $booked_date = Appointment::select('time')
                            ->where( 'year', $date_format['year'] )
                            ->where( 'month', $date_format['month'] )
                            ->where( 'day', $date_format['day'] )
                            ->get();
        $date_array = [];
        foreach( $booked_date as $date ) {
            $date_array[] = $date->time;
        }

        $available = array_diff( $this->availableTimes, $date_array );
        return $available;
    }

    public function setAppointment(Request $request){
        /**
         * Create a new appointment in the Appoinment table
         *
         * @param  \Illuminate\Http\Request  $request
         * @return object JSON output of the created appointment
         */
        $date = $request['date'];
        $date_format = $this->dateFormat($date);
        $time = $request['time'];
        $customer = $request['customerId'];

        $appointment = new Appointment;
        $appointment->customer_id   = $customer;
        $appointment->day           = $date_format['day'];
        $appointment->month         = $date_format['month'];
        $appointment->year          = $date_format['year'];
        $appointment->time          = $time;
        $appointment->date_string   = $date;
        $appointment->save();

        //$this->sendEmail($appointment);

        return $appointment;
    }

    public function getAppointments(Request $request) {
         /**
         * Return the appointments given post data of year, month and day
         *
         * @param  \Illuminate\Http\Request  $request
         * @return array Returns an array of appointments
         */
        $date = $request['date'];
        $date_format = $this->dateFormat($date);

        $bookeddates = Appointment::where( 'year', $date_format['year'] )
                                    ->where( 'month', $date_format['month'] )
                                    ->where( 'day', $date_format['day'] )
                                    ->orderBy( 'time' )
                                    ->get();
        return AppointmentsResource::collection($bookeddates);
    }

    public function blockAppointments(Request $request){
        /**
         * Creates an appointment for every slot in a day
         *
         * @param  \Illuminate\Http\Request  $request
         * @return
         */
        $date = $request['date'];
        $date_format = $this->dateFormat($date);

        foreach($this->availableTimes as $times ) {
            $appointment = new Appointment;
            $appointment->customer_id   = 0;
            $appointment->day           = $date_format['day'];
            $appointment->month         = $date_format['month'];
            $appointment->year          = $date_format['year'];
            $appointment->time          = $times;
            $appointment->date_string   = $date;
            $appointment->save();
        }
    }

    public function sendEmail($data){
        $customer_id = $data->customer_id;
        $customerdetails = Customer::where('id', $customer_id)->first();

            Mail::send('emails.newappointment', ['data' => $data], function ($m) use ( $customerdetails ) {
                $m->from('paul@haydensgarage.com', 'Appointment Booked');
                $m->to( $customerdetails->email, $customerdetails->name)->subject('Appointment Booked');
                $m->cc( 'leon.kimpton@010983@yahoo.co.uk',  'Leon Kimpton')->subject('Appointment Booked');
            });
    }

    public function dateFormat($date){
        $day = date("d", strtotime($date));
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        return [
            'day'       => $day,
            'month'     => $month,
            'year'      => $year
        ];
    }
}
