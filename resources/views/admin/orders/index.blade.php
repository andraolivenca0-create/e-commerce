@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-lg-8">
        {{-- List Item --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Item Pesanan</h5>
            </div>
            <div class="card-body">
                @foreach($order->items as $item)
                    <div class="d-flex mb-3">
                        <img src="{{ $item->product->image_url }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                            <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                        </div>
                        <div class="fw-bold">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fs-5 fw-bold">
                    <span>Total Pembayaran</span>
                    <span class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Info Customer --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Info Customer</h5>
            </div>
            <div class="card-body">
                <p class="mb-1 fw-bold">{{ $order->user->name }}</p>
                <p class="mb-1 text-muted">{{ $order->user->email }}</p>
            </div>
        </div>

        {{-- Action Card --}}
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Update Status Order</h6>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label small text-muted">Status Saat Ini: <strong>{{ ucfirst($order->status) }}</strong></label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing (Sedang Dikemas)</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Selesai/Dikirim)</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Batalkan & Restock)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Update Status
                    </button>
                </form>@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Daftar Pesanan</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        {{-- Filter Status --}}
        <ul class="nav nav-pills card-header-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'processing' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">Diproses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'shipped' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'shipped']) }}">Dikirim</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'delivered' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'delivered']) }}">Sampai</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">Batal</a>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No. Order</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#{{ $order->order_number }}</td>
                            <td>
                                <div class="fw-bold">{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info text-dark">Diproses</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary">Dikirim</span>
                                @elseif($order->status == 'delivered')
                                    <span class="badge bg-success">Sampai</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada pesanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $orders->links() }}
    </div>
</div>
@endsection

                @if($order->status == 'cancelled')
                    <div class="alert alert-danger mt-3 mb-0 small">
                        <i class="bi bi-info-circle"></i> Pesanan ini telah dibatalkan. Stok produk telah dikembalikan otomatis.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection