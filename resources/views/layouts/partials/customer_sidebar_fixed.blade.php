<aside class="tw-sticky tw-top-0 tw-z-30 tw-flex tw-h-screen tw-overflow-y-auto tw-bg-white tw-w-64 tw-flex-col tw-shrink-0 tw-border-r tw-border-gray-200">
    <div class="tw-px-4 tw-py-4 tw-border-b tw-border-gray-100">
        <p class="tw-text-gray-800 tw-font-semibold">Account</p>
    </div>
    <nav class="tw-flex-1 tw-py-2">
        <ul class="tw-space-y-1">
            <li>
                <a href="{{ url('/customer') }}" class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">
                    <span>My Account</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/') }}" class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/my-orders') }}" class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">
                    <span>My Orders</span>
                </a>
            </li>
            <li>
                <a href="#" class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">
                    <span>Invoices</span>
                </a>
            </li>
            <li>
                <a href="#" class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">
                    <span>Order Templates</span>
                </a>
            </li>
            <li>
                <a href="#" class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">
                    <span>Speed Order</span>
                </a>
            </li>
            <li>
                <a href="#" class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">
                    <span>Addresses</span>
                </a>
            </li>
            <li>
                <a href="#" class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">
                    <span>Personal Settings</span>
                </a>
            </li>
            <li class="tw-pt-2 tw-mt-2 tw-border-t tw-border-gray-100">
                <form method="POST" action="{{ route('customer.logout') }}" class="tw-mx-2">
                    @csrf
                    <button type="submit" class="tw-w-full tw-text-left tw-px-2.5 tw-py-2.5 hover:tw-bg-gray-100 tw-text-gray-700">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
