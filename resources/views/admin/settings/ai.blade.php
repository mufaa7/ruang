@extends('layouts.admin')

@section('header')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold tracking-tight text-black">AI Settings</h1>
    <span class="text-[12px] bg-slate-100 px-3 py-1 border border-black font-medium">Provider Configuration</span>
</div>
@endsection

@section('content')
<div class="space-y-6 max-w-5xl">
    
    <!-- MINI DASHBOARD -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="dev-card p-4 flex flex-col justify-between">
            <span class="text-[11px] font-bold text-slate-500 uppercase">AI Status</span>
            <div class="flex items-center gap-2 mt-2">
                @php $hasActive = false; @endphp
                @foreach($providers as $k => $p)
                    @if(!empty($settings["ai_{$k}_key"]))
                        @php $hasActive = true; @endphp
                    @endif
                @endforeach
                @if($hasActive)
                    <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                    <span class="text-[13px] font-bold">Active</span>
                @else
                    <span class="h-2.5 w-2.5 rounded-full bg-yellow-500"></span>
                    <span class="text-[13px] font-bold">Untested</span>
                @endif
            </div>
        </div>
        <div class="dev-card p-4 flex flex-col justify-between col-span-2 md:col-span-1">
            <span class="text-[11px] font-bold text-slate-500 uppercase">Default Provider</span>
            <span class="text-[13px] font-bold mt-2 capitalize">{{ $settings['active_ai_provider'] ?? 'None' }}</span>
        </div>
        <div class="dev-card p-4 flex flex-col justify-between">
            <span class="text-[11px] font-bold text-slate-500 uppercase">Requests Today</span>
            <span class="text-xl font-black mt-1">{{ number_format($stats['requests_today'] ?? 0) }}</span>
        </div>
        <div class="dev-card p-4 flex flex-col justify-between">
            <span class="text-[11px] font-bold text-slate-500 uppercase">Tokens / Cost</span>
            <span class="text-[13px] font-bold mt-2">{{ number_format($stats['tokens_today'] ?? 0) }} <br><span class="text-green-700">Rp{{ number_format($stats['cost_today'] ?? 0) }}</span></span>
        </div>
        <div class="dev-card p-4 flex flex-col justify-between">
            <span class="text-[11px] font-bold text-slate-500 uppercase">Avg Latency</span>
            <span class="text-xl font-black mt-1">{{ $stats['avg_latency'] ?? 0 }}s</span>
        </div>
    </div>

    @if(session('success'))
        <div class="dev-card p-4 bg-green-50 border-l-4 border-l-green-600 mb-6">
            <span class="text-[13px] font-bold text-green-800">SUCCESS:</span>
            <span class="text-[13px] text-green-700 ml-2">{{ session('success') }}</span>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="dev-card p-4 bg-red-50 border-l-4 border-l-red-600 mb-6">
            <span class="text-[13px] font-bold text-red-800">ERROR:</span>
            <ul class="text-[13px] text-red-700 ml-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN: FORM -->
        <div class="md:col-span-2 space-y-6">
            <div class="dev-card p-6">
                <form method="POST" action="{{ route('admin.ai_settings.store') }}" class="space-y-8" id="aiSettingsForm">
                    @csrf

                    <!-- SECTION: Providers Registry -->
                    <div class="mb-8">
                        <h2 class="text-[14px] font-bold text-black border-b border-black pb-2 mb-4 uppercase">Providers Registry</h2>
                        <p class="text-[12px] text-slate-500 mb-4 font-bold">Configure credentials and default settings for each AI provider.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($providers as $key => $prov)
                                <div class="border border-black p-4 bg-slate-50 relative group">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-[13px] font-bold flex items-center gap-2">
                                            {{ $prov['name'] }}
                                        </h3>
                                        @if(!empty($settings["ai_{$key}_key"]))
                                            <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 border border-green-700 font-bold">CONNECTED</span>
                                        @else
                                            <span class="text-[10px] bg-slate-200 text-slate-600 px-2 py-0.5 border border-slate-600 font-bold">NOT CONFIGURED</span>
                                        @endif
                                    </div>
                                    <button type="button" onclick="openProviderModal('{{ $key }}', '{{ $prov['name'] }}')" class="mt-3 text-[11px] font-bold border border-black px-3 py-1 bg-white hover:bg-slate-100 uppercase w-full flex justify-between items-center">
                                        <span>Configure</span>
                                        <span>→</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- SECTION: Global Configuration -->
                    <div>
                        <h2 class="text-[14px] font-bold text-black border-b border-black pb-2 mb-4 uppercase flex justify-between items-center">
                            Global Variables
                        </h2>
                        
                        <div class="space-y-5">
                            <div>
                                <label for="active_ai_provider" class="block text-[12px] font-bold text-black mb-1">DEFAULT_PROVIDER</label>
                                <select id="active_ai_provider" name="active_ai_provider" class="w-full text-[13px] text-black border border-black rounded-none px-3 py-2 focus:ring-0 focus:border-black bg-white">
                                    @foreach($providers as $key => $prov)
                                        <option value="{{ $key }}" {{ ($settings['active_ai_provider'] ?? '') == $key ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="ai_temperature" class="block text-[12px] font-bold text-black mb-1 flex justify-between">
                                        <span>TEMPERATURE</span>
                                        <span id="tempValueDisplay" class="text-blue-700 font-bold">{{ $settings['ai_temperature'] ?? '0.7' }}</span>
                                    </label>
                                    <input type="range" step="0.1" min="0" max="2" id="ai_temperature" name="ai_temperature" value="{{ $settings['ai_temperature'] ?? '0.7' }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-black">
                                </div>
                                <div>
                                    <label for="ai_max_tokens" class="block text-[12px] font-bold text-black mb-1">MAX_TOKENS</label>
                                    <input type="number" id="ai_max_tokens" name="ai_max_tokens" value="{{ $settings['ai_max_tokens'] ?? '' }}" class="w-full text-[13px] text-black bg-white border border-black rounded-none px-3 py-2 focus:ring-0 focus:border-black placeholder:text-slate-400" placeholder="e.g. 2000">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: Feature Routing -->
                    <div class="pt-4 border-t border-black mt-8">
                        <h2 class="text-[14px] font-bold text-black border-b border-black pb-2 mb-4 uppercase">
                            Feature Override
                        </h2>
                        <p class="text-[12px] text-slate-500 mb-4 font-bold">
                            Override the default provider for specific features. <br>
                            If "Use Default" is ON, the feature will use the <strong>Global DEFAULT_PROVIDER</strong> and the model configured inside that provider's settings.
                        </p>
                        
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($features as $fKey => $fData)
                                <div class="border border-black p-4 bg-slate-50/50">
                                    <div class="flex justify-between items-center mb-3">
                                        <h3 class="text-[13px] font-bold text-black flex items-center gap-2">{{ $fData['icon'] }} {{ $fData['name'] }}</h3>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="ai_{{ $fKey }}_use_default" class="feature-toggle peer sr-only" data-target="config_{{ $fKey }}" {{ ($settings["ai_{$fKey}_use_default"] ?? '1') == '1' ? 'checked' : '' }}>
                                            <div class="w-8 h-4 bg-slate-300 rounded-full peer peer-checked:bg-green-500 relative transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                                            <span class="text-[11px] font-bold text-slate-600 select-none">Use Default</span>
                                        </label>
                                    </div>
                                    <div id="config_{{ $fKey }}" class="space-y-3 {{ ($settings["ai_{$fKey}_use_default"] ?? '1') == '1' ? 'hidden' : '' }}">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 mb-1">PROVIDER</label>
                                                <select id="ai_{{ $fKey }}_provider" name="ai_{{ $fKey }}_provider" class="feature-provider-select w-full text-[12px] text-black border border-black rounded-none px-2 py-1.5 focus:border-black bg-white" data-target="ai_{{ $fKey }}_model">
                                                    @foreach($providers as $key => $prov)
                                                        <option value="{{ $key }}" {{ ($settings["ai_{$fKey}_provider"] ?? '') == $key ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 mb-1">MODEL</label>
                                                <select id="ai_{{ $fKey }}_model_select" class="feature-model-select w-full text-[12px] text-black border border-black rounded-none px-2 py-1.5 focus:border-black bg-white mb-1" data-target="ai_{{ $fKey }}_model">
                                                </select>
                                                <input type="text" id="ai_{{ $fKey }}_model" name="ai_{{ $fKey }}_model" class="w-full text-[12px] text-black border border-black rounded-none px-2 py-1.5 focus:border-black bg-white placeholder:text-slate-400" placeholder="Enter custom model..." value="{{ $settings["ai_{$fKey}_model"] ?? '' }}" style="display: none;">
                                                <input type="hidden" id="saved_ai_{{ $fKey }}_model" value="{{ $settings["ai_{$fKey}_model"] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- ACTION -->
                    <div class="pt-4 flex justify-end border-t border-black mt-8">
                        <button type="submit" class="px-6 py-2 text-[13px] font-bold uppercase tracking-wider bg-black text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black border border-black">
                            Commit Changes
                        </button>
                    </div>
                    
                    <!-- Hidden inputs holding the actual provider data for form submission -->
                    <div id="hiddenProviderInputs" class="hidden">
                        @foreach($providers as $key => $prov)
                            <input type="hidden" name="ai_{{ $key }}_key" id="main_ai_{{ $key }}_key" value="{{ old("ai_{$key}_key", $settings["ai_{$key}_key"] ?? '') }}">
                            <input type="hidden" name="ai_{{ $key }}_base_url" id="main_ai_{{ $key }}_base_url" value="{{ old("ai_{$key}_base_url", $settings["ai_{$key}_base_url"] ?? $prov['default_base_url']) }}">
                            <input type="hidden" name="ai_{{ $key }}_model" id="main_ai_{{ $key }}_model" value="{{ old("ai_{$key}_model", $settings["ai_{$key}_model"] ?? '') }}">
                            <input type="hidden" name="ai_{{ $key }}_timeout" id="main_ai_{{ $key }}_timeout" value="{{ old("ai_{$key}_timeout", $settings["ai_{$key}_timeout"] ?? 120) }}">
                            <input type="hidden" name="ai_{{ $key }}_retry" id="main_ai_{{ $key }}_retry" value="{{ old("ai_{$key}_retry", $settings["ai_{$key}_retry"] ?? 0) }}">
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: STATUS -->
        <div class="space-y-6">
            <div class="dev-card p-6">
                <h2 class="text-[14px] font-bold text-black border-b border-black pb-2 mb-4 uppercase">Provider Readiness</h2>
                
                <ul class="space-y-3 text-[13px] font-mono">
                    @foreach($providers as $key => $prov)
                        <li class="flex justify-between items-center">
                            <span class="font-bold text-black">{{ $prov['name'] }}</span>
                            @if(!empty($settings["ai_{$key}_key"]))
                                <span class="text-green-600 flex items-center gap-1 font-bold">🟢 Ready</span>
                            @else
                                <span class="text-slate-400 flex items-center gap-1">○ Not configured</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- ACTIVE FEATURES MAPPING -->
            <div class="dev-card p-6">
                <h2 class="text-[14px] font-bold text-black border-b border-black pb-2 mb-4 uppercase">Current Active Model</h2>
                <p class="text-[11px] text-slate-500 mb-4 font-medium leading-tight">This is what your website is actively using right now based on your settings.</p>
                <ul class="space-y-3 text-[13px]">
                    @foreach($features as $fKey => $fData)
                        <li class="flex flex-col mb-1 pb-2 border-b border-slate-100 last:border-0">
                            <span class="font-bold text-black flex items-center gap-1">{{ $fData['icon'] }} {{ $fData['name'] }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="bg-black text-white px-2 py-0.5 text-[10px] uppercase font-bold">{{ $activeMapping[$fKey]['provider'] }}</span>
                                <span class="text-slate-600 font-mono text-[11px]">{{ $activeMapping[$fKey]['model'] }}</span>
                                @if($activeMapping[$fKey]['is_default'])
                                    <span class="text-[9px] text-slate-400 italic">(Default)</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Container for Providers -->
<div id="providerModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white border border-black w-full max-w-lg shadow-xl shadow-black/20 flex flex-col max-h-[90vh]">
        <div class="flex justify-between items-center p-4 border-b border-black bg-slate-50">
            <h2 id="modalTitle" class="text-[14px] font-bold text-black uppercase tracking-wide">Configure Provider</h2>
            <button type="button" onclick="closeProviderModal()" class="text-xl font-bold text-black hover:text-red-600 focus:outline-none leading-none">&times;</button>
        </div>
        
        <div class="p-6 overflow-y-auto space-y-4">
            <!-- These hidden fields help the JS map to the main form inputs -->
            <input type="hidden" id="modalProviderKey">
            
            <div>
                <label class="block text-[12px] font-bold text-black mb-1">API_KEY</label>
                <input type="text" id="modalApiKey" class="w-full text-[13px] text-black border border-black rounded-none px-3 py-2 focus:ring-0 focus:border-black bg-slate-50 font-mono placeholder:text-slate-400" placeholder="sk-...">
            </div>
            
            <div>
                <label class="block text-[12px] font-bold text-black mb-1">BASE_URL</label>
                <input type="url" id="modalBaseUrl" class="w-full text-[13px] text-black bg-white border border-black rounded-none px-3 py-2 focus:ring-0 focus:border-black font-mono">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-[12px] font-bold text-black mb-1">DEFAULT_MODEL</label>
                    <select id="modalModelSelect" class="w-full text-[13px] text-black border border-black rounded-none px-3 py-2 focus:ring-0 focus:border-black bg-white mb-2">
                    </select>
                    <input type="text" id="modalModel" class="w-full text-[13px] text-black border border-black rounded-none px-3 py-2 focus:ring-0 focus:border-black bg-white placeholder:text-slate-400" placeholder="Enter custom model..." style="display: none;">
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-black mb-1">TIMEOUT (s)</label>
                    <input type="number" id="modalTimeout" class="w-full text-[13px] text-black bg-white border border-black rounded-none px-3 py-2 focus:ring-0 focus:border-black font-mono placeholder:text-slate-400" placeholder="120">
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-black mb-1">MAX_RETRIES</label>
                    <input type="number" id="modalRetry" class="w-full text-[13px] text-black bg-white border border-black rounded-none px-3 py-2 focus:ring-0 focus:border-black font-mono placeholder:text-slate-400" placeholder="0">
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-dashed border-slate-300">
                <div class="flex justify-between items-center">
                    <button type="button" onclick="runTestConnection()" class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider text-black flex items-center gap-2 border border-black hover:bg-slate-100">
                        <span id="testIcon">🔌</span> Test Connection
                    </button>
                    <span id="testResult" class="text-[11px] font-mono hidden px-2 py-1 border border-transparent"></span>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-black bg-slate-50 flex justify-end gap-3">
            <button type="button" onclick="closeProviderModal()" class="px-4 py-2 text-[12px] font-bold text-slate-600 hover:text-black">CANCEL</button>
            <button type="button" onclick="saveProviderModal()" class="bg-black text-white px-6 py-2 text-[12px] font-bold uppercase hover:bg-slate-800 border border-black">Apply to Form</button>
        </div>
    </div>
</div>

<script>
    // Known models for each provider
    const availableModels = {
        'nineinference': [
            {id: 'deepseek-v4-pro', name: 'DeepSeek V4 Pro'},
            {id: 'deepseek-v3', name: 'DeepSeek V3'},
            {id: 'qwen-3', name: 'Qwen 3'}
        ],
        'kelontong': [
            {id: 'gpt-5.6-luna', name: 'GPT-5.6 Luna'},
            {id: 'gpt-5.6-sol', name: 'GPT-5.6 Sol'},
            {id: 'gpt-5.6-terra', name: 'GPT-5.6 Terra'},
            {id: 'glm-5.2', name: 'GLM 5.2'},
            {id: 'deepseek-v4-flash', name: 'DeepSeek V4 Flash'},
            {id: 'deepseek-v4-pro', name: 'DeepSeek V4 Pro'},
            {id: 'kimi-k2.7-code', name: 'Kimi K2.7 Code'},
            {id: 'kimi-k2.7-code-highspeed', name: 'Kimi K2.7 Code Highspeed'},
            {id: 'gemini-3.6-flash', name: 'Gemini 3.6 Flash'}
        ],
        'gemini': [
            {id: 'gemini-2.5-flash', name: 'Gemini 2.5 Flash'},
            {id: 'gemini-2.5-pro', name: 'Gemini 2.5 Pro'}
        ],
        'openrouter': [
            {id: 'anthropic/claude-3.5-sonnet', name: 'Claude 3.5 Sonnet'},
            {id: 'meta-llama/llama-3.1-70b', name: 'Llama 3.1 70B'}
        ]
    };

    function populateSelect(selectEl, provider, savedVal) {
        const models = availableModels[provider] || [];
        selectEl.innerHTML = '';
        let found = false;
        models.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.name;
            if (m.id === savedVal) {
                opt.selected = true;
                found = true;
            }
            selectEl.appendChild(opt);
        });
        if (savedVal && !found) {
            const opt = document.createElement('option');
            opt.value = savedVal;
            opt.textContent = savedVal + ' (Custom)';
            opt.selected = true;
            selectEl.appendChild(opt);
        }
        const customOpt = document.createElement('option');
        customOpt.value = '__custom__';
        customOpt.textContent = '-- Custom Model --';
        selectEl.appendChild(customOpt);
    }

    // Modal Logic
    function openProviderModal(providerKey, providerName) {
        document.getElementById('modalTitle').textContent = providerName + ' Configuration';
        document.getElementById('modalProviderKey').value = providerKey;
        
        // Load data from hidden form inputs
        document.getElementById('modalApiKey').value = document.getElementById('main_ai_' + providerKey + '_key').value;
        document.getElementById('modalBaseUrl').value = document.getElementById('main_ai_' + providerKey + '_base_url').value;
        document.getElementById('modalTimeout').value = document.getElementById('main_ai_' + providerKey + '_timeout').value;
        document.getElementById('modalRetry').value = document.getElementById('main_ai_' + providerKey + '_retry').value;
        
        const savedModel = document.getElementById('main_ai_' + providerKey + '_model').value;
        const selectModel = document.getElementById('modalModelSelect');
        const inputModel = document.getElementById('modalModel');
        
        populateSelect(selectModel, providerKey, savedModel);
        
        if (savedModel && !Array.from(selectModel.options).some(opt => opt.value === savedModel && opt.value !== '__custom__')) {
            selectModel.value = '__custom__';
            inputModel.style.display = 'block';
            inputModel.value = savedModel;
        } else {
            inputModel.style.display = 'none';
            inputModel.value = savedModel;
        }
        
        // Add event listener to modal select
        selectModel.onchange = function() {
            if (this.value === '__custom__') {
                inputModel.style.display = 'block';
                inputModel.value = '';
                inputModel.focus();
            } else {
                inputModel.style.display = 'none';
                inputModel.value = this.value;
            }
        };
        
        // Reset test UI
        document.getElementById('testIcon').textContent = '🔌';
        document.getElementById('testResult').classList.add('hidden');
        
        document.getElementById('providerModal').classList.remove('hidden');
    }

    function closeProviderModal() {
        document.getElementById('providerModal').classList.add('hidden');
    }

    function saveProviderModal() {
        const providerKey = document.getElementById('modalProviderKey').value;
        
        // Apply back to hidden form inputs
        document.getElementById('main_ai_' + providerKey + '_key').value = document.getElementById('modalApiKey').value;
        document.getElementById('main_ai_' + providerKey + '_base_url').value = document.getElementById('modalBaseUrl').value;
        document.getElementById('main_ai_' + providerKey + '_model').value = document.getElementById('modalModel').value;
        document.getElementById('main_ai_' + providerKey + '_timeout').value = document.getElementById('modalTimeout').value;
        document.getElementById('main_ai_' + providerKey + '_retry').value = document.getElementById('modalRetry').value;
        
        closeProviderModal();
        
        alert('Applied! Don\'t forget to click "Commit Changes" to save permanently.');
    }

    function runTestConnection() {
        const provider = document.getElementById('modalProviderKey').value;
        const apiKey = document.getElementById('modalApiKey').value;
        const baseUrl = document.getElementById('modalBaseUrl').value;
        const model = document.getElementById('modalModel').value;
        const testIcon = document.getElementById('testIcon');
        const testResult = document.getElementById('testResult');

        testIcon.textContent = '⏳';
        testResult.classList.remove('hidden', 'border-green-700', 'text-green-700', 'border-red-700', 'text-red-700');
        testResult.classList.add('border-black', 'text-black');
        testResult.textContent = 'Testing...';

        fetch("{{ route('admin.ai_settings.test') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                provider: provider,
                api_key: apiKey,
                base_url: baseUrl,
                model: model
            })
        })
        .then(res => res.json())
        .then(data => {
            testIcon.textContent = '🔌';
            testResult.classList.remove('border-black', 'text-black');
            if (data.success) {
                testResult.classList.add('border-green-700', 'text-green-700');
                testResult.innerHTML = `✅ ${data.latency}`;
                testResult.classList.remove('hidden');
            } else {
                testResult.classList.add('border-red-700', 'text-red-700');
                testResult.textContent = '🔴 Failed';
                testResult.classList.remove('hidden');
                alert(data.message);
            }
        })
        .catch(err => {
            testIcon.textContent = '🔌';
            testResult.classList.remove('border-black', 'text-black');
            testResult.classList.add('border-red-700', 'text-red-700');
            testResult.textContent = '🔴 Error';
            testResult.classList.remove('hidden');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Temperature slider
        const tempSlider = document.getElementById('ai_temperature');
        const tempDisplay = document.getElementById('tempValueDisplay');
        if(tempSlider) {
            tempSlider.addEventListener('input', function() {
                tempDisplay.textContent = parseFloat(this.value).toFixed(1);
            });
        }

        // Feature Toggles
        document.querySelectorAll('.feature-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const targetId = this.getAttribute('data-target');
                const targetEl = document.getElementById(targetId);
                if (this.checked) {
                    targetEl.classList.add('hidden');
                } else {
                    targetEl.classList.remove('hidden');
                }
            });
        });

        // Feature Model Select Custom toggle
        document.querySelectorAll('.feature-model-select').forEach(select => {
            select.addEventListener('change', function() {
                const targetId = this.getAttribute('data-target');
                const inputEl = document.getElementById(targetId);
                if (this.value === '__custom__') {
                    inputEl.style.display = 'block';
                    inputEl.value = '';
                    inputEl.focus();
                } else {
                    inputEl.style.display = 'none';
                    inputEl.value = this.value;
                }
            });
        });

        // Feature Model population
        document.querySelectorAll('.feature-provider-select').forEach(select => {
            select.addEventListener('change', function() {
                const targetId = this.getAttribute('data-target');
                const targetSelect = document.getElementById(targetId + '_select');
                const targetInput = document.getElementById(targetId);
                
                // Get the globally saved default model for this provider
                const globalModel = document.getElementById('main_ai_' + this.value + '_model').value;
                
                populateSelect(targetSelect, this.value, globalModel);
                
                // Auto inherit the global model
                targetInput.value = globalModel;
                
                // Check if it's custom
                if (globalModel && !Array.from(targetSelect.options).some(opt => opt.value === globalModel && opt.value !== '__custom__')) {
                    targetSelect.value = '__custom__';
                    targetInput.style.display = 'block';
                } else {
                    targetSelect.value = globalModel;
                    targetInput.style.display = 'none';
                }
            });
            
            // Initialize on load
            const targetId = select.getAttribute('data-target');
            const targetSelect = document.getElementById(targetId + '_select');
            const targetInput = document.getElementById(targetId);
            const savedVal = document.getElementById('saved_' + targetId).value;
            
            populateSelect(targetSelect, select.value, savedVal);
            
            if (savedVal && !Array.from(targetSelect.options).some(opt => opt.value === savedVal && opt.value !== '__custom__')) {
                targetSelect.value = '__custom__';
                targetInput.style.display = 'block';
            } else {
                targetSelect.value = savedVal;
                targetInput.style.display = 'none';
            }
        });
        
        // Form intercept: when pressing enter in modal, don't submit main form
        document.getElementById('providerModal').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveProviderModal();
            }
        });
    });
</script>
@endsection
