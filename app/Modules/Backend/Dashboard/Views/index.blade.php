@extends('backend.master')

@push('meta')
<title>
    {!! __('backend.dashboard') . '&nbsp;-&nbsp;' . implode(" ", explode("_", env('APP_NAME'))) !!}
</title>
@endpush

@section('content')
<div class="panel-header">
    <div class="header text-center">
        <h2 class="title">Dashboard</h2>
        <p class="category">Handcrafted by our friends from
            <a target="_blank" href="https://fullcalendar.io/">FullCalendar.io</a>. Please checkout their
            <a href="https://fullcalendar.io/docs/" target="_blank">full documentation</a>.</p>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="statistics">
                                <div class="info">
                                    <div class="icon icon-primary">
                                        <i class="now-ui-icons ui-2_chat-round"></i>
                                    </div>
                                    <h3 class="info-title">859</h3>
                                    <h6 class="stats-title">Comments</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="statistics">
                                <div class="info">
                                    <div class="icon icon-success">
                                        <i class="now-ui-icons files_single-copy-04"></i>
                                    </div>
                                    <h3 class="info-title">
                                        {{count(Auth::user()->has_blogs)}}
                                    </h3>
                                    <h6 class="stats-title">Posts</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="statistics">
                                <div class="info">
                                    <div class="icon icon-info">
                                        <i class="now-ui-icons users_single-02"></i>
                                    </div>
                                    <h3 class="info-title">562</h3>
                                    <h6 class="stats-title">Followers</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="statistics">
                                <div class="info">
                                    <div class="icon icon-danger">
                                        <i class="now-ui-icons objects_support-17"></i>
                                    </div>
                                    <h3 class="info-title">353</h3>
                                    <h6 class="stats-title">Content Requests</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-timeline card-plain">
                <div class="card-body">
                    <ul class="timeline">
                        <li class="timeline-inverted">
                            <div class="timeline-badge danger">
                                <i class="now-ui-icons files_paper"></i>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <span class="badge badge-danger">Some Title</span>
                                </div>
                                <div class="timeline-body">
                                    <p>Wifey made the best Father's Day meal ever. So thankful so happy so blessed.
                                        Thank you for making my family We just had fun with the “future” theme !!! It
                                        was a fun night all together ... The always rude Kanye Show at 2am Sold Out
                                        Famous viewing @ Figueroa and 12th in downtown.</p>
                                </div>
                                <h6>
                                    <i class="ti-time"></i> 11 hours ago via Twitter
                                </h6>
                            </div>
                        </li>
                        <li>
                            <div class="timeline-badge success">
                                <i class="now-ui-icons shopping_tag-content"></i>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <span class="badge badge-success">Another One</span>
                                </div>
                                <div class="timeline-body">
                                    <p>Thank God for the support of my wife and real friends. I also wanted to point out
                                        that it’s the first album to go number 1 off of streaming!!! I love you Ellen
                                        and also my number one design rule of anything I do from shoes to music to homes
                                        is that Kim has to like it....</p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-badge info">
                                <i class="now-ui-icons shopping_delivery-fast"></i>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <span class="badge badge-info">Another Title</span>
                                    {{-- <div class="dropdown pull-right">
                                        <button type="button" class="btn btn-round btn-info dropdown-toggle"
                                            data-toggle="dropdown">
                                            <i class="now-ui-icons design_bullet-list-67"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="timeline-body">
                                    <p>Called I Miss the Old Kanye That’s all it was Kanye And I love you like Kanye
                                        loves Kanye Famous viewing @ Figueroa and 12th in downtown LA 11:10PM</p>
                                    <p>What if Kanye made a song about Kanye Royère doesn't make a Polar bear bed but
                                        the Polar bear couch is my favorite piece of furniture we own It wasn’t any
                                        Kanyes Set on his goals Kanye</p>
                                    <hr>
                                </div>
                                <div class="timeline-footer">
                                    <div class="row">
                                        <div class="col">
                                            <span class="badge badge-success">Comments</span>
                                        </div>
                                        <div class="col">
                                            <div class="dropdown pull-right">
                                                <button type="button" class="btn btn-round btn-info dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <i class="now-ui-icons design_bullet-list-67"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Action</a>
                                                    <a class="dropdown-item" href="#">Another action</a>
                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="timeline-badge warning">
                                <i class="now-ui-icons ui-1_email-85"></i>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <span class="badge badge-warning">Another One</span>
                                </div>
                                <div class="timeline-body">
                                    <p>Tune into Big Boy's 92.3 I'm about to play the first single from Cruel Winter
                                        also to Kim’s hair and makeup Lorraine jewelry and the whole style squad at
                                        Balmain and the Yeezy team. Thank you Anna for the invite thank you to the whole
                                        Vogue team</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('customJS')
<script>
    $(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
    });
</script>
@endpush