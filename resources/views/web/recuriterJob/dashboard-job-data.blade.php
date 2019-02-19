<!--Job listing-->
<ul class="dashboarFinalList recentlyPost ">
  @foreach ($jobList as $job)
    <li>
      <div class="template-job-information ">
        <div class="template-job-information-left">
          <h4>{{ $job['jobtitle_name'] }}</h4>
        </div>
      </div>

      @include('web.recuriterJob.partial.job-type')

      <div class="postViewDetail text-right">
        <a href="{{ url('/job/details') }}/{{ $job['id'] }}">View details</a>
      </div>
    </li>
  @endforeach
</ul>
