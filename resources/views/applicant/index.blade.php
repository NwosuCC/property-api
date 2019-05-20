@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row no-gutters align-items-center">
                            <div class="">
                                <h2>{{ __('Applicants')  }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Interests</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($applicants as $i => $applicant)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $applicant->name  }}</td>
                                        <td>{{ $applicant->email  }}</td>
                                        <td>{{ $applicant->applications_count }}</td>
                                        <td class="px-0 text-center">
                                          <a class="nav-link p-0" href="{{ $applicant->route->applicant->show }}">
                                            {{ __('View >>') }}
                                          </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
