@extends('layouts.app')

@section('title', 'Edit Deployed Item')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Edit Deployed Item</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <button type="button" class="btn btn-outline-primary shadow-md mr-2" onclick="showQRCode('{{ $deployedItem->qr_code ?? $deployedItem->qrCode }}')">
            <i data-lucide="qrcode" class="w-4 h-4 mr-2"></i> Show QR Code
        </button>
        <a href="{{ route('deployed-items.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Deployed Items
        </a>
    </div>
</div>

<div class="intro-y box p-5 mt-5">
    <form method="POST" action="{{ route('deployed-items.update', $deployedItem) }}" class="grid grid-cols-12 gap-6">
        @csrf
        @method('PUT')
        
        <!-- Item Name -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="itemName" class="form-label">Item Name <span class="text-danger">*</span></label>
                <input type="text" id="itemName" name="itemName" class="form-control w-full @error('itemName') border-danger @enderror" value="{{ old('itemName', $deployedItem->itemName) }}" required>
                @error('itemName')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Department -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="departmentID" class="form-label">Department <span class="text-danger">*</span></label>
                <select id="departmentID" name="departmentID" class="form-select w-full @error('departmentID') border-danger @enderror" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $id => $name)
                        <option value="{{ $id }}" {{ old('departmentID', $deployedItem->departmentID ?? $deployedItem->department_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('departmentID')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Item Category -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="itemCategory" class="form-label">Category <span class="text-danger">*</span></label>
                <input type="text" id="itemCategory" name="itemCategory" class="form-control w-full @error('itemCategory') border-danger @enderror" value="{{ old('itemCategory', $deployedItem->itemCategory) }}" required>
                @error('itemCategory')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Date Deployed -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="dateDeployed" class="form-label">Date Deployed <span class="text-danger">*</span></label>
                <input type="date" id="dateDeployed" name="dateDeployed" class="form-control w-full @error('dateDeployed') border-danger @enderror" value="{{ old('dateDeployed', $deployedItem->dateDeployed ? $deployedItem->dateDeployed->format('Y-m-d') : '') }}" required>
                @error('dateDeployed')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Quantity -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" id="quantity" name="quantity" min="1" class="form-control w-full @error('quantity') border-danger @enderror" value="{{ old('quantity', $deployedItem->quantity) }}" required>
                @error('quantity')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Cost -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="cost" class="form-label">Cost <span class="text-danger">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <span class="text-gray-500">₱</span>
                    </div>
                    <input type="number" id="cost" name="cost" step="0.01" min="0" class="form-control w-full pl-8 @error('cost') border-danger @enderror" value="{{ old('cost', $deployedItem->cost) }}" required>
                </div>
                @error('cost')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Status -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select id="status" name="status" class="form-select w-full @error('status') border-danger @enderror" required>
                    <option value="active" {{ old('status', $deployedItem->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $deployedItem->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="maintenance" {{ old('status', $deployedItem->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="retired" {{ old('status', $deployedItem->status) == 'retired' ? 'selected' : '' }}>Retired</option>
                </select>
                @error('status')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Condition -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="condition" class="form-label">Condition <span class="text-danger">*</span></label>
                <select id="condition" name="condition" class="form-select w-full @error('condition') border-danger @enderror" required>
                    <option value="excellent" {{ old('condition', $deployedItem->condition) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                    <option value="good" {{ old('condition', $deployedItem->condition) == 'good' ? 'selected' : '' }}>Good</option>
                    <option value="fair" {{ old('condition', $deployedItem->condition) == 'fair' ? 'selected' : '' }}>Fair</option>
                    <option value="poor" {{ old('condition', $deployedItem->condition) == 'poor' ? 'selected' : '' }}>Poor</option>
                </select>
                @error('condition')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Purpose -->
        <div class="col-span-12">
            <div class="input-form">
                <label for="purpose" class="form-label">Purpose</label>
                <textarea id="purpose" name="purpose" class="form-control w-full @error('purpose') border-danger @enderror" rows="3">{{ old('purpose', $deployedItem->purpose) }}</textarea>
                @error('purpose')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Item Description -->
        <div class="col-span-12">
            <div class="input-form">
                <label for="itemDescription" class="form-label">Description</label>
                <textarea id="itemDescription" name="itemDescription" class="form-control w-full @error('itemDescription') border-danger @enderror" rows="3">{{ old('itemDescription', $deployedItem->itemDescription) }}</textarea>
                @error('itemDescription')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- QR Code -->
        <div class="col-span-12 md:col-span-6">
            <div class="input-form">
                <label for="qr_code" class="form-label">QR Code <span class="text-danger">*</span></label>
                <div class="flex">
                    <input type="text" id="qr_code" name="qr_code" 
                           class="form-control w-full rounded-r-none @error('qr_code') border-danger @enderror" 
                           value="{{ old('qr_code', $deployedItem->qr_code ?? $deployedItem->qrCode) }}"
                           required readonly>
                    <button type="button" onclick="generateQRCode()"
                            class="btn btn-outline-secondary rounded-l-none border-l-0 px-4">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </button>
                </div>
                @error('qr_code')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Remarks -->
        <div class="col-span-12">
            <div class="input-form">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea id="remarks" name="remarks" class="form-control w-full @error('remarks') border-danger @enderror" rows="2">{{ old('remarks', $deployedItem->remarks) }}</textarea>
                @error('remarks')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="col-span-12 flex items-center justify-center sm:justify-end mt-5">
            <button type="submit" class="btn btn-primary w-24 mr-2">Update</button>
            <a href="{{ route('deployed-items.index') }}" class="btn btn-secondary w-24">Cancel</a>
        </div>
    </form>
</div>

<!-- QR Code Modal -->
<div class="modal" id="qr-modal">
    <div class="modal__content">
        <div class="p-5 text-center">
            <div id="qrcode" class="mx-auto"></div>
            <div class="mt-4">
                <button type="button" class="btn btn-secondary mt-3" onclick="closeModal()">Close</button>
                <button type="button" class="btn btn-primary mt-3" onclick="printQRCode()">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function showQRCode(qrData) {
        document.getElementById('qrcode').innerHTML = '';
        new QRCode(document.getElementById("qrcode"), {
            text: qrData,
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
        document.getElementById('qr-modal').classList.add('show');
    }
    
    function closeModal() {
        document.getElementById('qr-modal').classList.remove('show');
    }
    
    function printQRCode() {
        const printWindow = window.open('', '_blank');
        const qrCodeContent = document.getElementById('qrcode').innerHTML;
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>QR Code - {{ $deployedItem->itemName }}</title>
                <style>
                    body { text-align: center; padding: 20px; }
                    .qrcode { margin: 0 auto; }
                    .item-name { font-size: 18px; font-weight: bold; margin: 10px 0; }
                    .item-code { font-family: monospace; margin: 10px 0; }
                    @media print {
                        @page { margin: 0; }
                        body { padding: 15mm; }
                    }
                </style>
            </head>
            <body>
                <div class="item-name">{{ $deployedItem->itemName }}</div>
                <div class="item-code">{{ $deployedItem->qr_code ?? $deployedItem->qrCode }}</div>
                <div class="qrcode">${qrCodeContent}</div>
                <script>window.onload = () => window.print()<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
    
    function generateQRCode() {
        // Generate a new QR code with timestamp and random string
        const timestamp = Date.now();
        const randomString = Math.random().toString(36).substring(2, 8).toUpperCase();
        const newQRCode = `DEP-${timestamp}-${randomString}`;
        document.getElementById('qr_code').value = newQRCode;
    }
</script>
@endpush

@endsection
