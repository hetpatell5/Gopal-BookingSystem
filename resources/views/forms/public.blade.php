<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0ebff; min-height: 100vh; padding: 32px 16px 64px; color: #1c2238; }
        .wrap { max-width: 640px; margin: 0 auto; }

        .form-header { background: white; border-top: 8px solid #f0b44b; padding: 24px; margin-bottom: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
        .form-header h1 { font-size: 22px; font-weight: 800; }
        .form-header p  { font-size: 13px; color: #6b7280; margin-top: 6px; }
        .req-note       { font-size: 11px; color: #ef4444; margin-top: 10px; }

        .field-card { background: white; padding: 20px; margin-bottom: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
        .field-card label { display: block; font-size: 13px; font-weight: 600; color: #1c2238; margin-bottom: 8px; }
        .field-card .desc { font-size: 11px; color: #9ca3af; margin-top: -4px; margin-bottom: 8px; }

        input[type=text], input[type=email], input[type=number], input[type=date], textarea, select {
            width: 100%; border: none; border-bottom: 1px solid #d1d5db; padding: 6px 0; font-size: 13px;
            outline: none; background: transparent; color: #1c2238; transition: border-color .15s;
        }
        input[type=text]:focus, input[type=email]:focus, input[type=number]:focus, input[type=date]:focus,
        textarea:focus, select:focus { border-bottom-color: #f0b44b; }
        textarea { resize: vertical; min-height: 60px; }
        select { border: 1px solid #d1d5db; padding: 6px 10px; border-radius: 0; background: white; }
        select:focus { border-color: #f0b44b; }

        .radio-opt, .check-opt { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 13px; color: #374151; cursor: pointer; }
        input[type=radio], input[type=checkbox] { accent-color: #f0b44b; width: 16px; height: 16px; }

        .error { font-size: 11px; color: #ef4444; margin-top: 4px; display: flex; align-items: center; gap: 4px; }

        .section-hdr { border-bottom: 2px solid #f0b44b; padding-bottom: 10px; }
        .section-hdr h2 { font-size: 16px; font-weight: 700; }
        .section-hdr p  { font-size: 12px; color: #9ca3af; margin-top: 4px; }

        .submit-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 0 32px; }
        .btn-submit { background: #f0b44b; color: #1c2238; font-weight: 800; font-size: 13px; border: none; padding: 12px 32px; cursor: pointer; letter-spacing:.02em; }
        .btn-submit:hover { background: #e0a43b; }
        .btn-clear { font-size: 12px; color: #9ca3af; border: none; background: none; cursor: pointer; }
        .btn-clear:hover { color: #6b7280; }

        .success-card { background: white; padding: 40px; text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
        .success-icon { width: 56px; height: 56px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 22px; color: #22c55e; }

        .brand { text-align: center; font-size: 11px; color: #a78bfa; margin-bottom: 20px; }
        .brand i { margin-right: 4px; }
    </style>
</head>
<body>
<div class="wrap">

    <p class="brand"><i class="fa-solid fa-bus"></i> Gopal Booking System</p>

    @if(session('submitted'))
    {{-- Success state --}}
    <div class="success-card">
        <div class="success-icon"><i class="fa-solid fa-check"></i></div>
        <h2 style="font-size:20px;font-weight:800;margin-bottom:8px;">Thank you!</h2>
        <p style="font-size:13px;color:#6b7280;">Your response to <strong>{{ $form->name }}</strong> has been received successfully.</p>
    </div>

    @else
    {{-- Form --}}
    <div class="form-header">
        <h1>{{ $form->name }}</h1>
        @if($form->description) <p>{{ $form->description }}</p> @endif
        <p class="req-note">* Required</p>
    </div>

    @if($errors->any())
        <div style="background:#fee2e2;border:1px solid #fca5a5;padding:12px 16px;margin-bottom:10px;font-size:13px;color:#ef4444;">
            Please fill all required fields.
        </div>
    @endif

    <form method="POST" action="{{ route('forms.public.submit', $form) }}">
        @csrf

        @foreach($form->fields as $field)
            @if($field['type'] === 'section_header')
                <div class="field-card section-hdr">
                    <h2>{{ $field['label'] }}</h2>
                    @if(!empty($field['description'])) <p>{{ $field['description'] }}</p> @endif
                </div>
            @else
                @php
                    $name    = 'responses[' . $field['label'] . ']';
                    $val     = old('responses.' . $field['label'], '');
                    $hasErr  = $errors->has('responses.' . $field['label']);
                @endphp
                <div class="field-card">
                    <label>
                        {{ $field['label'] }}
                        @if(!empty($field['required'])) <span style="color:#ef4444;">*</span> @endif
                    </label>
                    @if(!empty($field['description']))
                        <p class="desc">{{ $field['description'] }}</p>
                    @endif

                    @switch($field['type'])
                        @case('short_text')
                            <input type="text" name="{{ $name }}" value="{{ $val }}"
                                   placeholder="{{ $field['placeholder'] ?? 'Your answer' }}">
                        @break
                        @case('email')
                            <input type="email" name="{{ $name }}" value="{{ $val }}"
                                   placeholder="{{ $field['placeholder'] ?? 'example@email.com' }}">
                        @break
                        @case('number')
                            <input type="number" name="{{ $name }}" value="{{ $val }}"
                                   placeholder="{{ $field['placeholder'] ?? '' }}">
                        @break
                        @case('long_text')
                            <textarea name="{{ $name }}" placeholder="{{ $field['placeholder'] ?? 'Your answer' }}">{{ $val }}</textarea>
                        @break
                        @case('date')
                            <input type="date" name="{{ $name }}" value="{{ $val }}">
                        @break
                        @case('dropdown')
                            <select name="{{ $name }}">
                                <option value="">Choose an option</option>
                                @foreach($field['options'] ?? [] as $opt)
                                    <option value="{{ $opt }}" {{ $val === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        @break
                        @case('multiple_choice')
                            @foreach($field['options'] ?? [] as $opt)
                                <label class="radio-opt">
                                    <input type="radio" name="{{ $name }}" value="{{ $opt }}"
                                           {{ $val === $opt ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                            @endforeach
                        @break
                        @case('checkboxes')
                            @foreach($field['options'] ?? [] as $opt)
                                @php $cbName = 'responses[' . $field['label'] . '][]'; @endphp
                                <label class="check-opt">
                                    <input type="checkbox" name="{{ $cbName }}" value="{{ $opt }}"
                                           {{ is_array(old('responses.' . $field['label'])) && in_array($opt, old('responses.' . $field['label'], [])) ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                            @endforeach
                        @break
                    @endswitch

                    @if($hasErr)
                        <p class="error"><i class="fa-solid fa-circle-exclamation"></i> This field is required</p>
                    @endif
                </div>
            @endif
        @endforeach

        <div class="submit-row">
            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-paper-plane" style="margin-right:6px;"></i> Submit
            </button>
            <button type="reset" class="btn-clear">Clear form</button>
        </div>
    </form>
    @endif

</div>
</body>
</html>
