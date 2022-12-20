@if (isset($type))
    @switch($type)
        @case('states')
            <option value="">---Select State---</option>
        @break

        @case('districts')
            <option value="">---Select District---</option>
        @break

        @case('cities')
            <option value="">---Select City---</option>
        @break

        @case('brands')
            <option value="">---Select Brand---</option>
        @break

        @case('models')
            <option value="">---Select Model---</option>
        @break

        @case('colors')
            <option value="">---Select Color---</option>
        @break

        @default
    @endswitch
@endif
