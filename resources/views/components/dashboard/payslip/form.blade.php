@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.payslips.store') : route('dashboard.payslips.update', $payslip->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="$indexUrl ?? route('dashboard.payslips.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="user_id" title="user" :data="$users" :value="$payslip->user_id ?? ''"
                                                      allowClear defaultOption class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group required">
                            <x-dashboard.form._input name="period_month" type="number" min="1" max="12" :value="$payslip->period_month"/>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group required">
                            <x-dashboard.form._input name="period_year" type="number" min="2000" max="2100" :value="$payslip->period_year"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="base_amount" type="number" step="0.01" :value="$payslip->base_amount"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="net_total" type="number" step="0.01" :value="$payslip->net_total"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="bonus" type="number" step="0.01" :value="$payslip->bonus"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="deductions" type="number" step="0.01" :value="$payslip->deductions"/>
                        </div>
                    </div>
                </div>

                @if($viewMode !== 'show')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <x-dashboard.form._file name="pdf"/>
                            </div>
                        </div>
                    </div>
                @endif

                @if($payslip->pdf_path)
                    <div class="row">
                        <div class="col-lg-12">
                            <p>
                                <a href="{{ Storage::disk('public')->url($payslip->pdf_path) }}" target="_blank" rel="noopener">
                                    {{ __('label.pdf_path') }}
                                </a>
                            </p>
                        </div>
                    </div>
                @endif

                @if($payslip->id)
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ route('dashboard.payslips.download', $payslip->id) }}" class="btn btn-outline-primary btn-sm">
                                Download Generated PDF
                            </a>
                        </div>
                    </div>
                @endif
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/payslip/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
