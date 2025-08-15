@extends('layouts.app')

@section('title', 'Deployed Item Details')

@section('content')
<div class="mt-8">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Deployed Item Details</h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button type="button" class="btn btn-secondary" onclick="showQRCode('{{ $deployedItem->qrCode }}')">
                <i data-lucide="qrcode" class="w-4 h-4 mr-2"></i> Show QR Code
            </button>
            @if(config('app.debug'))
            <button type="button" class="btn btn-outline-secondary ml-2" onclick="testQRCode()">
                <i data-lucide="test-tube" class="w-4 h-4 mr-2"></i> Test QR
            </button>
            @endif
            <a href="{{ route('deployed-items.index') }}" class="btn btn-outline-secondary ml-2">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Overview
            </a>
        </div>
    </div>

    <!-- Debug Information (remove in production) -->
    @if(config('app.debug'))
    <div class="intro-y box p-4 mt-4 bg-yellow-50 border-l-4 border-yellow-400">
        <div class="flex">
            <div class="flex-shrink-0">
                <i data-lucide="info" class="w-5 h-5 text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Debug Info:</strong> QR Code Value: "{{ $deployedItem->qrCode }}" | 
                    Type: {{ gettype($deployedItem->qrCode) }} | 
                    Empty: {{ empty($deployedItem->qrCode) ? 'Yes' : 'No' }}
                </p>
            </div>
        </div>
    </div>
    @endif

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
                    <div class="font-mono text-xs bg-slate-100 dark:bg-darkmode-800 p-2 rounded inline-block">{{ $deployedItem->qrCode }}</div>
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
    </div>

    <!-- QR Code Modal -->
    <div id="qr-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 relative">
                <div class="p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">QR Code</h3>
                    <div id="qrcode" class="mx-auto mb-4 flex justify-center"></div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printQRCode()">
                            <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Global variables for debugging
        let qrCodeLibraryLoaded = false;
        let modalInitialized = false;

        // Wait for QR code library to load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking QR code library...');
            
            // Check if QRCode library is loaded
            if (typeof QRCode === 'undefined') {
                console.error('QRCode library not loaded');
                return;
            }
            
            qrCodeLibraryLoaded = true;
            console.log('QRCode library loaded successfully');
            
            // Initialize modal
            initializeModal();
        });

        function initializeModal() {
            const modal = document.getElementById('qr-modal');
            if (modal) {
                modalInitialized = true;
                console.log('Modal initialized successfully');
            } else {
                console.error('Modal element not found');
            }
        }

        function showQRCode(qrData) {
            console.log('=== QR Code Debug Info ===');
            console.log('showQRCode called with:', qrData);
            console.log('qrData type:', typeof qrData);
            console.log('qrData length:', qrData ? qrData.length : 'undefined');
            console.log('QR Code library loaded:', qrCodeLibraryLoaded);
            console.log('Modal initialized:', modalInitialized);
            console.log('========================');
            
            if (!qrCodeLibraryLoaded) {
                alert('QR Code library not loaded. Please refresh the page and try again.');
                return;
            }
            
            if (!modalInitialized) {
                console.log('Modal not initialized, initializing now...');
                initializeModal();
            }
            
            // If no QR code data, generate a fallback using the deployed item ID
            if (!qrData || qrData.trim() === '') {
                console.log('No QR code data, generating fallback...');
                const fallbackData = 'DEPLOYED-ITEM-{{ $deployedItem->deployedID }}';
                qrData = fallbackData;
                console.log('Using fallback QR code data:', fallbackData);
            }

            console.log('Attempting to show QR code for:', qrData);

            const modal = document.getElementById('qr-modal');
            const qrcodeDiv = document.getElementById('qrcode');
            
            if (!modal || !qrcodeDiv) {
                console.error('Modal or QR code div not found');
                alert('Error: Modal elements not found. Please refresh the page.');
                return;
            }
            
            // Clear previous QR code
            qrcodeDiv.innerHTML = '';
            
            try {
                // Create new QR code
                new QRCode(qrcodeDiv, {
                    text: qrData,
                    width: 200,
                    height: 200,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                
                // Show modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                console.log('QR Code generated successfully');
                console.log('Modal should now be visible');
            } catch (error) {
                console.error('Error generating QR code:', error);
                alert('Error generating QR code: ' + error.message + '. Please try again.');
            }
        }
        
        function closeModal() {
            const modal = document.getElementById('qr-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                console.log('Modal closed');
            }
        }
        
        function printQRCode() {
            const printWindow = window.open('', '_blank');
            const qrCodeContent = document.getElementById('qrcode').innerHTML;
            const itemName = '{{ $deployedItem->itemName }}';
            const qrCode = '{{ $deployedItem->qrCode }}';
            
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
                        .qrcode { 
                            margin: 0 auto; 
                            display: inline-block;
                        }
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
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('qr-modal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Additional debugging: log when the script loads
        console.log('QR Code script loaded');

        // Test function for debugging
        function testQRCode() {
            console.log('Testing QR code functionality...');
            const testData = 'TEST-QR-CODE-' + Date.now();
            console.log('Using test data:', testData);
            showQRCode(testData);
        }
    </script>
    @endpush
@endsection
