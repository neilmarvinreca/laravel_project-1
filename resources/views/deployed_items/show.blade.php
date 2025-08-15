@extends('layouts.app')

@section('title', 'Deployed Item Details')

@section('content')
<div class="mt-8">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Deployed Item Details</h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button type="button" class="btn btn-secondary" onclick="showQRCode('{{ $deployedItem->qrCode ?? $deployedItem->qr_code ?? 'N/A' }}')">
                <i data-lucide="qrcode" class="w-4 h-4 mr-2"></i> Show QR Code
            </button>
            <button type="button" class="btn btn-outline-secondary ml-2" onclick="testModal()">
                <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Test Modal
            </button>
            <a href="{{ route('deployed-items.index') }}" class="btn btn-outline-secondary ml-2">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Overview
            </a>
        </div>
    </div>

    <div class="intro-y box p-5 mt-5">
        <div class="grid grid-cols-12 gap-4">
            <!-- Item Information -->
            <div class="col-span-12 md:col-span-6">
                <div class="border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <h2 class="text-lg font-medium">Item Information</h2>
                </div>
            
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <div class="text-slate-500">Item Name</div>
                        <div class="font-medium text-lg">{{ $deployedItem->itemName }}</div>
                    </div>
                
                <div class="md:col-span-2">
                    <div class="text-slate-500">Description</div>
                    <div class="font-medium">{{ $deployedItem->itemDescription ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <div class="text-slate-500">Category</div>
                    <div class="font-medium">{{ $deployedItem->itemCategory }}</div>
                </div>
                
                <div>
                    <div class="text-slate-500">Date Acquired</div>
                    <div class="font-medium">{{ optional($deployedItem->dateAcquired)->format('M d, Y') ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <div class="text-slate-500">Cost</div>
                    <div class="font-medium">₱{{ number_format($deployedItem->cost, 2) }}</div>
                </div>
                
                <div>
                    <div class="text-slate-500">Status</div>
                    <div>
                        <span class="px-2 py-1 rounded-full text-xs {{ 
                            $deployedItem->status === 'active' ? 'bg-success text-white' : 'bg-warning text-white' 
                        }}">
                            {{ ucfirst($deployedItem->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <div class="text-slate-500">QR Code</div>
                    <div class="font-mono text-xs bg-slate-100 dark:bg-darkmode-800 p-2 rounded inline-block">{{ $deployedItem->qrCode ?? $deployedItem->qr_code ?? 'N/A' }}</div>
                    <!-- Debug Information -->
                    <div class="mt-2 text-xs text-gray-500">
                        <div>Debug: qrCode = "{{ $deployedItem->qrCode }}"</div>
                        <div>Debug: qr_code = "{{ $deployedItem->qr_code }}"</div>
                        <div>Debug: Raw qr_code = "{{ $deployedItem->getRawOriginal('qr_code') }}"</div>
                        <div>Debug: All Attributes = {{ json_encode($deployedItem->getAttributes()) }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Deployment Information -->
            <div class="col-span-12 md:col-span-6">
                <div class="border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <h2 class="text-lg font-medium">Deployment Information</h2>
                </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-slate-500">Deployment ID</div>
                    <div class="font-medium">{{ $deployedItem->deployedID }}</div>
                </div>
                
                <div>
                    <div class="text-slate-500">Department</div>
                    <div class="font-medium">{{ optional($deployedItem->department)->officename ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <div class="text-slate-500">Date Deployed</div>
                    <div class="font-medium">{{ optional($deployedItem->dateDeployed)->format('M d, Y') ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <div class="text-slate-500">Deployed By</div>
                    <div class="font-medium">{{ optional($deployedItem->deployedBy)->name ?? 'System' }}</div>
                </div>
                
                @if($deployedItem->checkedBy)
                <div>
                    <div class="text-slate-500">Checked By</div>
                    <div class="font-medium">{{ $deployedItem->checkedBy->name }}</div>
                </div>
                @endif
                
                @if($deployedItem->remarks)
                <div class="md:col-span-2">
                    <div class="text-slate-500">Remarks</div>
                    <div class="font-medium bg-slate-50 dark:bg-darkmode-700 p-3 rounded">{{ $deployedItem->remarks }}</div>
                </div>
                @endif
                
                <div class="md:col-span-2 pt-4 border-t border-slate-200/60 dark:border-darkmode-400 mt-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-slate-500">Created At</div>
                            <div class="font-medium">{{ $deployedItem->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500">Last Updated</div>
                            <div class="font-medium">{{ $deployedItem->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
        <!-- Activity Log -->
        @if($deployedItem->activities->count() > 0)
        <div class="mt-8">
            <div class="border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                <h2 class="text-lg font-medium">Activity Log</h2>
            </div>
        
            <div class="relative">
                <div class="absolute left-5 top-0 h-full border-l-2 border-slate-200 dark:border-darkmode-400"></div>
                
                @foreach($deployedItem->activities as $activity)
                <div class="relative mb-6 ml-10">
                    <div class="absolute -left-10 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                        <i data-lucide="{{ $activity->event === 'created' ? 'plus' : ($activity->event === 'updated' ? 'edit' : 'trash-2') }}" class="w-4 h-4"></i>
                    </div>
                    <div class="bg-slate-50 dark:bg-darkmode-600 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div class="font-medium">{{ $activity->description }}</div>
                            <div class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                        @if($activity->properties->has('attributes'))
                            <div class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                @foreach($activity->properties['attributes'] as $key => $value)
                                    @if(!in_array($key, ['updated_at', 'created_at', 'deleted_at']))
                                        <div class="grid grid-cols-3 gap-2 py-1">
                                            <div class="col-span-1 font-medium">{{ str_replace('_', ' ', ucfirst($key)) }}:</div>
                                            <div class="col-span-2">{{ $value ?? 'N/A' }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

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
        <!-- Primary QR Code Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <!-- Fallback QR Code Library -->
        <script>
            if (typeof QRCode === 'undefined') {
                console.log('Primary QR library failed, trying fallback...');
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
                script.onload = function() {
                    console.log('Fallback QR library loaded successfully');
                };
                script.onerror = function() {
                    console.error('Both QR libraries failed to load');
                };
                document.head.appendChild(script);
            }
        </script>
        <style>
            /* Ensure modal displays properly */
            .modal {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
            }
            
            .modal.show {
                display: block;
            }
            
            .modal__content {
                background-color: white;
                margin: 15% auto;
                padding: 20px;
                border-radius: 8px;
                width: 80%;
                max-width: 400px;
                position: relative;
            }
            
            #qrcode {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 200px;
            }
            
            #qrcode img {
                max-width: 100%;
                height: auto;
            }
        </style>
        <script>
            function showQRCode(qrData) {
                console.log('showQRCode called with:', qrData); // Debug log
                
                // Check if QR data is valid
                if (!qrData || qrData === 'N/A') {
                    alert('No QR code data available for this item.');
                    return;
                }
                
                // Clear previous QR code
                document.getElementById('qrcode').innerHTML = '';
                
                try {
                    // Ensure QR data is a string
                    const qrText = String(qrData).trim();
                    
                    if (!qrText) {
                        throw new Error('Empty QR code data');
                    }
                    
                    // Generate new QR code
                    new QRCode(document.getElementById("qrcode"), {
                        text: qrText,
                        width: 200,
                        height: 200,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                    
                    console.log('QR code generated successfully for:', qrText); // Debug log
                    
                    // Show modal
                    const modal = document.getElementById('qr-modal');
                    modal.classList.add('show');
                    console.log('Modal should be visible now'); // Debug log
                } catch (error) {
                    console.error('Error generating QR code:', error);
                    
                    // Fallback: Create a simple text representation
                    const qrContainer = document.getElementById("qrcode");
                    qrContainer.innerHTML = `
                        <div style="text-align: center; padding: 20px;">
                            <div style="font-size: 24px; margin-bottom: 10px;">⚠️</div>
                            <div style="font-family: monospace; font-size: 12px; word-break: break-all; background: #f0f0f0; padding: 10px; border-radius: 4px;">
                                ${qrData}
                            </div>
                            <div style="margin-top: 10px; color: #666; font-size: 12px;">
                                QR code generation failed, showing data as text
                            </div>
                        </div>
                    `;
                    
                    // Show modal anyway
                    const modal = document.getElementById('qr-modal');
                    modal.classList.add('show');
                }
            }
            
            function testModal() {
                const modal = document.getElementById('qr-modal');
                const qrContainer = document.getElementById("qrcode");
                qrContainer.innerHTML = `
                    <div style="text-align: center; padding: 20px;">
                        <div style="font-size: 24px; margin-bottom: 10px;">🧪</div>
                        <div style="font-size: 16px; margin-bottom: 10px;">Modal Test</div>
                        <div style="color: #666; font-size: 12px;">
                            If you can see this, the modal is working correctly
                        </div>
                    </div>
                `;
                modal.classList.add('show');
                console.log('Test modal opened');
            }
            
            function closeModal() {
                document.getElementById('qr-modal').classList.remove('show');
            }
            
            function printQRCode() {
                try {
                    const printWindow = window.open('', '_blank');
                    const qrCodeContent = document.getElementById('qrcode').innerHTML;
                    const itemName = '{{ $deployedItem->itemName }}';
                    const qrCode = '{{ $deployedItem->qrCode ?? $deployedItem->qr_code ?? "N/A" }}';
                    
                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>QR Code - ${itemName}</title>
                            <style>
                                body { 
                                    text-align: center; 
                                    padding: 20px; 
                                    font-family: Arial, sans-serif;
                                }
                                .qrcode { margin: 0 auto; }
                                .item-name { 
                                    font-size: 18px; 
                                    font-weight: bold; 
                                    margin: 10px 0; 
                                }
                                .item-code { 
                                    font-family: monospace; 
                                    margin: 10px 0; 
                                    font-size: 14px;
                                }
                                @media print {
                                    @page { margin: 0; }
                                    body { padding: 15mm; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="item-name">${itemName}</div>
                            <div class="item-code">${qrCode}</div>
                            <div class="qrcode">${qrCodeContent}</div>
                            <script>window.onload = () => window.print()<\/script>
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                } catch (error) {
                    console.error('Error printing QR code:', error);
                    alert('Error printing QR code. Please try again.');
                }
            }
            
            // Close modal when clicking outside
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, setting up modal event listeners'); // Debug log
                
                const modal = document.getElementById('qr-modal');
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            closeModal();
                        }
                    });
                    
                    // Test modal functionality
                    console.log('Modal element found:', modal);
                    console.log('Modal display style:', window.getComputedStyle(modal).display);
                    console.log('Modal z-index:', window.getComputedStyle(modal).zIndex);
                } else {
                    console.error('Modal element not found');
                }
                
                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeModal();
                    }
                });
                
                // Debug: Check if QR code library is loaded
                if (typeof QRCode !== 'undefined') {
                    console.log('QRCode library loaded successfully');
                } else {
                    console.error('QRCode library not loaded');
                }
                
                // Test modal visibility
                setTimeout(() => {
                    console.log('Testing modal visibility...');
                    testModal();
                    setTimeout(() => {
                        closeModal();
                        console.log('Modal test completed');
                    }, 2000);
                }, 1000);
            });
        </script>
        @endpush
    </div>
@endsection
