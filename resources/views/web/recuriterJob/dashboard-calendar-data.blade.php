<ul class="weekList">
  @foreach ($currentWeekCalendar as $key=>$calendar)
    <li>{{ date('M d - D',strtotime($key)) }}
      @if(!empty($calendar))
        <div class="dental">
          <p>{{ $calendar['jobTitle'] }}</p>
          @if(!empty($calendar['seekerData']))
            <div class="dentalImg">
              @if(isset($calendar['seekerData'][0]))
                <img src="{{ $calendar['seekerData'][0]['profile_pic'] }}" width="22" class="img-circle">
              @endif
              @if(isset($calendar['seekerData'][1]))
                <img src="{{ $calendar['seekerData'][1]['profile_pic'] }}" width="22" class="img-circle">
              @endif
              @if(count($calendar['seekerData'])>2)
                <div class="dentalNumber img-circle">{{ count($calendar['seekerData'])-2 }}+</div>
              @endif
            </div>
          @endif
        </div>
        @if($calendar['jobCount']>1)
          <a href="{{ url('calender')}}" class="moreJobs pull-right">{{ $calendar['jobCount']-1 }} More Positions</a>
        @endif
      @endif
    </li>
  @endforeach
</ul>