import { useEffect, useState } from "react";
import axios from "axios";
import { Select } from "../../../components/fields";

export default function Customers({ selectedId, setSelectedId, selectedCustomer, setSelectedCustomer }) {
    const [customers, setCustomers] = useState([]);

    // fetch customers from API
    useEffect(() => {
        async function fetchCustomers() {
            try {
                const res = await axios.get("https://promotion-engine-production.up.railway.app/api/customers");
                setCustomers(res.data);
            } catch (err) {
                console.error("Error fetching customers:", err);
            }
        }

        fetchCustomers();
    }, []);

    useEffect(() => {
        const customer = customers.find((c) => c.id === +selectedId);
        setSelectedCustomer(customer || null);
    }, [selectedId, customers, setSelectedCustomer]);

    return (
        <div className="rounded-md space-y-5">
            <Select
                value={selectedId}
                onChange={(e) => setSelectedId(e.target.value)}
                placeholder="Customers"
                options={customers.map((c) => ({
                    value: c.id,
                    label: `${c.email}`,
                }))}
            />

            <div className="flex items-center justify-center bg-blue-100 rounded-md p-3 min-h-40 w-full">
                {selectedCustomer && (
                    <div className="flex flex-col gap-3 w-full h-full">
                        <p><strong className="text-sm">Email:</strong> {selectedCustomer.email}</p>
                        <p><strong className="text-sm">City:</strong> {selectedCustomer.city}</p>
                        <p><strong className="text-sm">Orders Count:</strong> {selectedCustomer.orders_count}</p>
                        <p><strong className="text-sm">Type:</strong> {selectedCustomer.type}</p>
                        <p><strong className="text-sm">Loyaly Tier:</strong> {selectedCustomer.loyalty_tier}</p>
                    </div>
                )}
                {!selectedCustomer && (

                    <span className="text-gray-500">Nothing Selected Yet</span>
                )}
            </div>
        </div>
    );
}
