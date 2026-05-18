@extends('layouts.customer')

@section('title', 'Free Embroidery File Converter - 1Dollar Digitizing')
@section('hero_title', 'Free Embroidery File Converter')
@section('hero_text', 'Convert your embroidery design files between any major format instantly. Free and secure — your file stays private.')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border: 1px solid #e0e0e0;">
                <div class="card-body">
                    <h2 class="card-title mb-3" style="color: #0066cc;">
                        🔄 Convert Your Embroidery File
                    </h2>
                    <p class="text-muted mb-4">
                        Upload any embroidery design file (DST, PES, JEF, EXP, VP3, XXX, EMB, VIP, HUS, etc.) and convert it to the format you need. Free, no registration, no email required. Maximum file size: 10&nbsp;MB.
                    </p>

                    <div class="mb-3">
                        <label for="convert_file" class="form-label fw-bold">1. Select your file</label>
                        <input type="file" id="convert_file" class="form-control"
                               accept=".dst,.pes,.jef,.exp,.vp3,.xxx,.emb,.vip,.hus,.dsb,.dsz,.pec,.pcd,.pcm,.pcq,.pcs,.tap,.u01,.zhs,.zxy" />
                        <small class="text-muted">Drag your design file here or click to browse.</small>
                    </div>

                    <div class="mb-4">
                        <label for="target_format" class="form-label fw-bold">2. Choose output format</label>
                        <select id="target_format" class="form-select">
                            <option value="dst" selected>DST (Tajima)</option>
                            <option value="pes">PES (Brother)</option>
                            <option value="jef">JEF (Janome)</option>
                            <option value="exp">EXP (Melco)</option>
                            <option value="vp3">VP3 (Husqvarna / Viking)</option>
                            <option value="xxx">XXX (Compucon / Singer)</option>
                            <option value="emb">EMB (Wilcom)</option>
                            <option value="vip">VIP (Husqvarna Viking)</option>
                            <option value="hus">HUS (Husqvarna)</option>
                            <option value="svg">SVG (Scalable Vector)</option>
                        </select>
                    </div>

                    <button type="button" id="convertBtn" class="btn btn-primary btn-lg w-100" style="min-height: 50px;">
                        <span id="convertBtnText">Convert &amp; Download</span>
                        <span id="convertSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                    </button>

                    <div id="convertSuccess" class="d-none mt-3 alert alert-success">
                        <strong>✓ Conversion successful!</strong> Your file is downloading. If it doesn't start automatically, check your browser's downloads.
                    </div>

                    <div id="convertError" class="d-none mt-3 alert alert-warning"></div>
                </div>
            </div>

            <div class="mt-4 text-center text-muted small">
                <p class="mb-1">Need professional digitizing? <a href="{{ url('/') }}" class="text-decoration-none fw-bold">Get a free quote →</a></p>
                <p class="mb-0" style="opacity: 0.7;">Your files are processed securely and not stored on our servers.</p>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const ROUTE = "{{ url('/tools/file-converter') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;

    const btn          = document.getElementById('convertBtn');
    const btnText      = document.getElementById('convertBtnText');
    const spinner      = document.getElementById('convertSpinner');
    const fileInput    = document.getElementById('convert_file');
    const formatSelect = document.getElementById('target_format');
    const errorPanel   = document.getElementById('convertError');
    const successPanel = document.getElementById('convertSuccess');

    btn.addEventListener('click', async function() {
        errorPanel.classList.add('d-none');
        successPanel.classList.add('d-none');

        if (!fileInput.files || fileInput.files.length === 0) {
            return showError('Please select an embroidery file to convert.');
        }

        const file = fileInput.files[0];
        if (file.size > 10 * 1024 * 1024) {
            return showError('File is too large. Maximum size is 10 MB.');
        }
        if (file.size === 0) {
            return showError('File appears to be empty. Please choose a different file.');
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('target_format', formatSelect.value);

        setLoading(true);

        try {
            const response = await fetch(ROUTE, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/octet-stream, application/json',
                },
                body: formData,
                credentials: 'same-origin',
            });

            if (!response.ok) {
                let msg = 'Conversion failed. Please try again.';
                try {
                    const errData = await response.json();
                    msg = errData.message || errData.detail || msg;
                } catch (e) {
                    msg = `Conversion failed (status ${response.status}).`;
                }
                return showError(msg);
            }

            const blob = await response.blob();
            const url = URL.createObjectURL(blob);

            const originalName = file.name.replace(/\.[^/.]+$/, '');
            const newName = originalName + '.' + formatSelect.value;

            const a = document.createElement('a');
            a.href = url;
            a.download = newName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            successPanel.classList.remove('d-none');
        } catch (err) {
            console.error('Conversion error', err);
            showError('Network error. Please check your connection and try again.');
        } finally {
            setLoading(false);
        }
    });

    function setLoading(isLoading) {
        btn.disabled = isLoading;
        btnText.textContent = isLoading ? 'Converting...' : 'Convert & Download';
        spinner.classList.toggle('d-none', !isLoading);
    }

    function showError(message) {
        errorPanel.textContent = message;
        errorPanel.classList.remove('d-none');
    }
})();
</script>
@endsection
