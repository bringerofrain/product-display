@extends('layout')
@section('content')
<?php $badge = '<span class="badge badge-secondary"> -> </span><br />';?>
<div>
    <h2>Admin Tracker</h2>
    <div id="layout" class="layout">
        <div id="accordion" role="tablist">
        <?php $x=0;  ?>

        @foreach($tracked as $key=>$client)
            <div class="card">
                <div class="card-header" role="tab" id="heading{{ $x }}" aria-expanded="true" aria-controls="collapse{{ $x }}" >
                    <h5 class="mb-0">
                        <div data-toggle="collapse" href="#collapse{{ $x }}" class="accord-header">
                            <div>
                            <label class="plabel">Session ID:</label> {{ $tracked[$key]['session_id'] }}
                            </div>
                            <div>
                            <label class="plabel">User Agent:</label> {{ $tracked[$key]['client'] }}
                            </div>
                            <div>
                            <label class="plabel">Mobile Device:</label> {{ ($tracked[$key]['mobile'])?'Yes':'No' }}
                            </div>
                        </div>
                    </h5>
                </div>

                <div id="collapse{{ $x }}" class="collapse" role="tabpanel" aria-labelledby="heading{{ $x }}" data-parent="#accordion">
                    <div class="card-body">
                        @if(count($client['data']))
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date / Time </th>
                                    <th>Path</th>
                                    <th>Action</th>
                                    <th style='width:250px;'>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($client['data'] as $thread)
                                <tr>
                                    <td><span class='date-tolocal'>{{ $thread->created_at }}</span></td>
                                    <td><?= ($thread->from_url=='')? 'Direct '.$badge.$thread->url : $thread->from_url.$badge.$thread->url ?></td>
                                    <td>{{ $thread->method }}</td>
                                    <td>{{ $thread->notes }}</td>
                                </tr>
                            @endforeach
                            <tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>


        <?php $x++;  ?>
        @endforeach

    </div><!--End accordion-->
</div> <!--End Layout-->

    <script>
        $(()=>{
            $.each($(".date-tolocal"),function(){
                var today = new Date($(this).text());
                var options = {hour:'numeric',minute:'2-digit'};
                $(this).html(today.toLocaleDateString('en-US',options));
            });

        });
    </script>
</div>



@stop
