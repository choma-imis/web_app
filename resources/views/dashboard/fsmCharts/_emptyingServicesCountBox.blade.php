@include ('layouts.dashboard.card',
 ['number' => number_format($emptyingServiceCount) ,
  'heading' => __("Service Providers"),
  'image' => asset('img/svg/imis-icons/applications-responded.svg')])
