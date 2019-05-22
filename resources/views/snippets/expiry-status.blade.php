<small>
  @if($expiry_date = $house->expires_at)
    @if($expired = $expiry_date->isPast())
      <i class="fa fa-times mr-2" style="color: red;"></i>
    @else
      <i class="fa fa-check mr-2" style="color: green;"></i>
    @endif
    {{ $house->expires_at_diff . ($expired ? ' ago' : ' left') }}
  @endif
</small>
