@extends('user.layouts.app')

@section('content')
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');


        .ticket-card {
            /* width: 320px; */
            height: 400px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgb(222 183 99) 0%, rgb(66 100 208) 100%);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            position: relative;
            color: white;
            padding: 20px;
            transition: transform 0.3s ease;
            perspective: 1000px;
        }

        .ticket-card:hover {
            transform: translateY(-10px);
        }

        .ticket-perforation {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: -10px;
            width: 80%;
            height: 20px;
            display: flex;
            justify-content: space-between;
        }

        .ticket-perforation span {
            display: block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .ticket-icon {
            font-size: 3rem;
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
            transition: all 0.5s ease;
        }

        .ticket-card:hover .ticket-icon {
            transform: scale(1.2);
            color: #FF9E00;
        }

        .ticket-title {
            font-size: 1.5rem;
            text-align: center;
            font-weight: 600;
            margin-bottom: 5px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .ticket-subtitle {
            text-align: center;
            opacity: 0.8;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .ticket-count {
            font-size: 3.5rem;
            font-weight: 700;
            text-align: center;
            transition: all 0.5s ease;
            margin: 20px 0;
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .ticket-count::after {
            content: "";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #FF9E00 50%, transparent 100%);
        }

        .ticket-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dashed rgba(255, 255, 255, 0.3);
        }

        .ticket-stat {
            text-align: center;
            flex: 1;
        }

        .ticket-stat-value {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .ticket-stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .ticket-ribbon {
            position: absolute;
            top: 15px;
            right: -40px;
            width: 150px;
            height: 40px;
            background-color: #FF9E00;
            transform: rotate(45deg);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 158, 0, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 158, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 158, 0, 0);
            }
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse-table {
            0% {
                box-shadow: 0 0 0 0 rgba(119, 0, 255, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 158, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 158, 0, 0);
            }
        }

        .animate-pulse-table{
            animation: pulse-table 2s infinite;
        }
    </style>
    <style>
        .card {

            border-radius: 8px;

            margin-bottom: 20px;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

        }



        .card-header {

            background-color: #8971ea;

            /* Orange header */

            color: white;

            border-bottom: 1px solid #8971ea;

            border-top-left-radius: 8px;

            border-top-right-radius: 8px;

            font-weight: bold;

        }

     


     



   



        .btn-circle {

            width: 40px;

            height: 40px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 1.2rem;

        }



        .input-group .form-control {

            background-color: #f8f9fa;

            border: 1px solid #ced4da;

            color: #333;

        }



        .btn-outline-secondarys {

            background-color: #8971ea;

            border-color: #8971ea;

            color: white;

        }

        .btn-outline-secondarys:hover {

            background-color: rgb(96, 62, 151);

            border-color: rgb(63, 36, 107);
            color: white;

        }

      
        .table tbody td {

            border-top: 1px solid #e9ecef;

        }

        .dashboard-card {

            

            border-radius: 8px;

            padding: 20px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

        }

        .dark-card-panel {

          

            /* White background for these panels */

            border-radius: 8px;

            padding: 20px;

            margin-bottom: 20px;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

        }



        .dark-card-panel h5 {

            /* Darker text for titles */

            margin-bottom: 15px;

        }






       



     



        .border-pro {

            border: 2px solid #8971ea;

            /* Orange border */

            border-radius: 8px;

            padding: 15px;

            background-color: #fff;

            /* White background for the bordered cards */

        }
    </style>
    

        <div class="container">

            <div class="col-md-12">

                <div class="row">

                    <div class="col-md-9">

                        {{-- Support Ticket Form --}}
                        <div class="card shadow-lg mb-4 border-0 ">
                            <div class="card-body ">
                                <h3 class="card-title text-orange fw-bold">New Support Ticket</h3>
                                <p class="text-muted">Need help? Fill out the form and our support team will get back to you
                                    as
                                    soon as possible.</p>

                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form action="{{ route('user.support.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="first_name" class="form-control"
                                            value="{{ auth()->user()->name }}" placeholder="Enter your name" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ auth()->user()->email }}" placeholder="Enter your email" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="message" rows="3" class="form-control"
                                            placeholder="Describe your issue">{{ old('message') }}</textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary " type="submit">Send</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">

                            <div class="ticket-card animate-pulse">
                                <div class="ticket-perforation">
                                    <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                </div>
                                <div class="ticket-ribbon">LIVE DATA</div>
                                <div class="ticket-icon">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <div class="ticket-title">Total Tickets</div>
                                <div class="ticket-subtitle">Current month count</div>
                                <div class="ticket-count">{{ $total }}</div>
                                <div class="ticket-stats">
                                    <div class="ticket-stat">
                                        <div class="ticket-stat-value">2</div>
                                        <div class="ticket-stat-label">Open</div>
                                    </div>
                                    <div class="ticket-stat">
                                        <div class="ticket-stat-value">{{ $total }}</div>
                                        <div class="ticket-stat-label">Closed</div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
               
            </div>
            
             <div class="col-md-12">
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="row ">
                                
                                <div class="col-md-3 mb-4">

                                  <div class="card dark-card-panel">

                                        <div class="d-flex align-items-center justify-content-center flex-column">

                                            <i class="fas fa-envelope-open text-success mb-2"
                                                style="font-size: 2.5rem;"></i>
                                            <h5 class="fw-semibold">Open Ticket</h5>


                                            <h4 class="text-success">{{ $openticket }}</h4>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-3 mb-4">

                                    <div class="card dark-card-panel">

                                        <div class="d-flex align-items-center justify-content-center flex-column">

                                            <i class="fas fa-hourglass-half text-warning mb-2"
                                                style="font-size: 2.5rem;"></i>
                                            <h5 class="fw-semibold">Pending Ticket</h5>


                                            <h4 class="text-warning">{{ $pending }}</h4>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-3 mb-4">

                                    <div class="card dark-card-panel">

                                        <div class="d-flex align-items-center justify-content-center flex-column">
                                            <i class="fas fa-spinner text-warning mb-2" style="font-size: 2.5rem;"></i>
                                            <h5 class="fw-semibold">Waiting Ticket</h5>



                                            <h4 class="text-warning">{{ $waiting }}</h4>

                                        </div>

                                    </div>

                                </div>
                                
                                <div class="col-md-3 mb-4">

                                <div class="card dark-card-panel">

                                        <div class="d-flex align-items-center justify-content-center flex-column">

                                            <i class="fas fa-check-circle text-success mb-2" style="font-size: 2.5rem;"></i>
                                            <h5 class="fw-semibold">Closed Ticket</h5>


                                            <h4 class="text-success">{{ $closed }}</h4>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    
                    @if($latestInquiry)
                        <div class="col-md-6">
                            <div class="card shadow border-0">
                                <div class="card-body">
                                    <h4 class="card-title text-orange">Latest Ticket</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th>Ticket</th>
                                                    <td>{{ $latestInquiry->ticket }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Created Time</th>
                                                    <td>{{ $latestInquiry->timestamp }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Message</th>
                                                    <td>{{ $latestInquiry->message }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>

            <div class="card dark-card-panel ">
                <div class="card-body">
                    <h4 class="card-title text-orange">Support Ticket</h4>
                    <div class="col-md-12">
                        <div class="card-datatable  table-responsive p-2">
                            <table class="table" id="SupportTicketTable">
                                <thead>
                                    <tr>
                                        <th>Ticket Id</th>
                                        <th>Description</th>
                                        <th>Create Date</th>
                                        <th>Status</th>
                                        <th>Reply</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
@section('scripts')

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>

        $(document).ready(function () {

            $('#SupportTicketTable').DataTable({

                processing: true,

                serverSide: true,

                pageLength: 5,

                lengthChange: false,



                ajax: '{{ route('user.support.supportticketdata') }}',

                columns: [

                    { data: 'ticket', name: 'ticket' },
                    { data: 'message', name: 'message' },
                    { data: 'timestamp', name: 'timestamp' },
                    { data: 'status', name: 'status' },
                    { data: 'reply', name: 'reply' },

                ],

                order: [[2, 'desc']],

            });



        });

    </script>

    <script>
        // Animation for counting up
        document.addEventListener('DOMContentLoaded', function () {
            const countElement = document.querySelector('.ticket-count');
            const targetCount = parseInt(countElement.textContent.replace(/,/g, ''));
            let currentCount = 0;
            const duration = 2000; // 2 seconds
            const increment = targetCount / (duration / 16); // 60fps

            const animateCount = () => {
                currentCount += increment;
                if (currentCount < targetCount) {
                    countElement.textContent = Math.floor(currentCount).toLocaleString();
                    requestAnimationFrame(animateCount);
                } else {
                    countElement.textContent = targetCount.toLocaleString();
                }
            };

            animateCount();

            // Hover effects
            const card = document.querySelector('.ticket-card');
            card.addEventListener('mouseenter', () => {
                card.classList.remove('animate-pulse');
            });
            card.addEventListener('mouseleave', () => {
                card.classList.add('animate-pulse');
            });
        });
    </script>
@endsection