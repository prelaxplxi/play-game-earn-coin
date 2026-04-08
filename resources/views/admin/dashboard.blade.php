@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard Overview</h3>
    </div>

    <div class="row g-4">
        <!-- Total Users -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Total Users</h6>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">{{ $totalUsers }}</h2>
                    <p class="mb-0 small text-white-50 mt-2">All registered users</p>
                </div>
            </div>
        </div>

        <!-- Total Games -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Total Games</h6>
                        <i class="fas fa-gamepad fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">{{ $totalGames }}</h2>
                    <p class="mb-0 small text-white-50 mt-2">Active games in system</p>
                </div>
            </div>
        </div>

        <!-- Today Withdraw -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Today's Withdrawals</h6>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">Rs. {{ number_format($todayWithdrawAmount, 2) }}</h2>
                    <p class="mb-0 small text-dark-50 mt-2">Total withdrawal amount today</p>
                </div>
            </div>
        </div>

        <!-- Today Earn Coins -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Today's Coin Earnings</h6>
                        <i class="fas fa-coins fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">{{ number_format($todayEarnCoins) }}</h2>
                    <p class="mb-0 small text-white-50 mt-2">Coins earned by users today</p>
                </div>
            </div>
        </div>

        <!-- Total Contests -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background-color: #4e73df !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Total Contests</h6>
                        <i class="fas fa-trophy fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">{{ $totalContests }}</h2>
                    <p class="mb-0 small text-white-50 mt-2">All created contests</p>
                </div>
            </div>
        </div>

        <!-- Active Contests -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background-color: #2e59d9 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Active Contests</h6>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">{{ $activeContests }}</h2>
                    <p class="mb-0 small text-white-50 mt-2">Currently running contests</p>
                </div>
            </div>
        </div>

        <!-- Total Surveys -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Total Surveys</h6>
                        <i class="fas fa-poll fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">{{ $totalSurveys }}</h2>
                    <p class="mb-0 small text-white-50 mt-2">All created surveys</p>
                </div>
            </div>
        </div>

        <!-- Total Responses -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-purple text-white" style="background-color: #6f42c1 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Total Responses</h6>
                        <i class="fas fa-reply-all fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">{{ $totalResponses }}</h2>
                    <p class="mb-0 small text-white-50 mt-2">Total user survey responses</p>
                </div>
            </div>
        </div>
        <!-- Today's Survey Responses -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background-color: #bf42c1 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Today's Survey Responses</h6>
                        <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-white">{{ $todayResponses }}</h2>
                    <p class="mb-0 small text-white-50 mt-2">Submissions received today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Survey-wise Responses Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Survey-wise Responses</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="surveyResponsesTable" class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Survey Title</th>
                                    <th>Total Responses</th>
                                    <th>Reward Coins</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#surveyResponsesTable').DataTable({
                "processing": true,
                "serverSide": false, // Since we are getting all data at once for dashboard, keeping it simple
                "ajax": "{{ route('admin.dashboard.survey-data') }}",
                "columns": [
                    { "data": "title" },
                    {
                        "data": "responses_count",
                        "render": function (data) {
                            return '<span class="badge text-dark">' + data + '</span>';
                        }
                    },
                    { "data": "total_reward_coins" },
                    {
                        "data": "is_active",
                        "render": function (data) {
                            if (data) {
                                return '<span class="badge bg-success">Active</span>';
                            } else {
                                return '<span class="badge bg-danger">Inactive</span>';
                            }
                        }
                    }
                ],
                "order": [[1, "desc"]],
                "pageLength": 5,
                "language": {
                    "search": "Filter surveys:",
                    "emptyTable": "No survey responses found"
                }
            });
        });
    </script>
@endpush