import { useEffect, useState } from "react";
import axios from "axios";
import { Select, Input } from "../../../components/fields";

export default function Products({ selectedProductId, setSelectedProductId, selectedProducts, setSelectedProducts, quantity, setQuantity }) {
    const [products, setProducts] = useState([]);

    // fetch customers from API
    useEffect(() => {
        async function fetchCustomers() {
            try {
                const res = await axios.get("http://localhost:8000/api/products");
                setProducts(res.data);

            } catch (err) {
                console.error("Error fetching customers:", err);
            }
        }

        fetchCustomers();
    }, []);

    useEffect(() => {
        const product = products.find((c) => c.id === +selectedProductId);
        setSelectedProducts(product || null);
    }, [selectedProductId, products, setSelectedProducts]);

    return (
        <div className="rounded-md space-y-5">
            <div className="flex items-center gap-4">
                <Select
                    value={selectedProductId}
                    onChange={(e) => setSelectedProductId(e.target.value)}
                    placeholder="Products"
                    options={products.map((c) => ({
                        value: c.id,
                        label: `${c.name}`,
                    }))}
                />

                <Input
                    type="number"
                    value={quantity}
                    placeholder="quantity"
                    min={1}
                    max={100}
                    onChange={(e) => {
                        const val = e.target.value;
                        if (val === "" || Number(val) > 0) {
                            setQuantity(val);
                        }
                    }}
                />
            </div>


            <div className="flex items-center justify-center bg-blue-100 rounded-md p-3 min-h-40 w-full">
                {selectedProducts && (
                    <div className="flex flex-col gap-3 w-full h-full">
                        <p><strong className="text-sm">Name:</strong> {selectedProducts.name}</p>
                        <p><strong className="text-sm">Category ID:</strong> {selectedProducts.category_id}</p>
                        <p><strong className="text-sm">Unit Price:</strong> {selectedProducts.unit_price}</p>
                        <p><strong className="text-sm">Quantity:</strong> {quantity}</p>
                    </div>

                )}
                {!selectedProducts && (

                    <span className="text-gray-500">Nothing Selected Yet</span>
                )}
            </div>
        </div>
    );
}
