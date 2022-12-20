@if (isset($type))
    @switch($type)
        @case('states')
            @isset($data)
                <option value="">---Select State---</option>
                @foreach ($data as $item)
                    <option value="{{ $item->id }}">{{ $item->state_name }}</option>
                @endforeach
            @endisset
        @break

        @case('districts')
            @isset($data)
                <option value="">---Select District---</option>
                @foreach ($data as $item)
                    <option value="{{ $item->id }}">{{ $item->district_name }}</option>
                @endforeach
            @endisset
        @break

        @case('cities')
            @isset($data)
                <option value="">---Select City---</option>
                @foreach ($data as $item)
                    <option value="{{ $item->id }}">{{ $item->city_name }}</option>
                @endforeach
            @endisset
        @break

        @case('brands')
            @isset($data)
                <option value="">---Select Brand---</option>
                @foreach ($data as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            @endisset
        @break

        @case('models')
            @isset($data)
                <option value="">---Select Model---</option>
                @foreach ($data as $item)
                    <option value="{{ $item->id }}">{{ $item->model_name }}</option>
                @endforeach
            @endisset
        @break

        @case('colors')
            @isset($data)
                <option value="">---Select Color---</option>
                @foreach ($data as $item)
                    <option value="{{ $item->id }}">{{ $item->color_name }}</option>
                @endforeach
            @endisset
        @break

        @default
    @endswitch
@endif
