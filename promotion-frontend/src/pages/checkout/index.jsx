import { useState, useEffect } from "react";
import axios from "axios";
import { evaluateRules } from "../../lib/ruleEngine";

import Customers from "./sections/customers";
import Products from "./sections/products";

export default function Checkout() {
    const [customer, setCustomer] = useState(null);
    const [selectedCustomerId, setSelectedCustomerId] = useState("");

    const [product, setProduct] = useState(null);
    const [selectedProductId, setSelectedProductId] = useState("");
    const [quantity, setQuantity] = useState(null);

    const [result, setResult] = useState(null);
    const [loading, setLoading] = useState(false);
    const [rules, setRules] = useState([]);

    useEffect(() => {
        const fetchRules = async () => {
            try {
                const res = await axios.get("http://localhost:8000/api/rules");
                setRules(res.data);
            } catch (err) {
                alert("Error fetching rules");
                console.error(err);
            }
        };
        fetchRules();
    }, []);

    const evaluate = async () => {
        setLoading(true);
        try {
            const facts = {
                line: {
                    productId: product.id,
                    quantity: quantity,
                    unitPrice: product.unit_price,
                    categoryId: product.category_id,
                },
                customer: {
                    email: customer.email,
                    type: customer.type,
                    loyaltyTier: customer.loyalty_tier,
                    ordersCount: customer.orders_Count,
                    city: customer.city
                },
                now: new Date().toISOString(), // Add current time for date-based rules
            };

            const evaluationResult = await evaluateRules(rules, facts);

            const finalLineTotal = (product.unit_price * quantity) - evaluationResult.totalDiscount;

            setResult({
                applied: evaluationResult.appliedRules.map(r => ({
                    ruleId: r.ruleId,
                    discount: r.discount,
                    ruleName: r.ruleName,
                })),
                totalDiscount: Number(evaluationResult.totalDiscount).toFixed(2),
                finalLineTotal: Number(finalLineTotal).toFixed(2),
            });
        } catch (err) {
            alert("Error during evaluation");
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-white to-blue-50/20 flex items-center justify-center p-4 xl:p-0">
            <div className="w-full xl:w-[75%] mx-auto bg-white border border-black/10 shadow px-5 py-4 rounded-md space-y-5">

                <div className="w-full border-b border-black/10 pb-3">
                    <h1 className="text-2xl font-bold text-blue-800">Checkout Evaluator</h1>
                </div>

                <div className="w-full grid grid-cols-1 lg:grid-cols-2 gap-10">
                    {/* Customers */}
                    <div className="flex flex-col gap-1">
                        <h2 className="font-semibold">Selcect a Customer</h2>
                        <Customers
                            selectedId={selectedCustomerId}
                            setSelectedId={setSelectedCustomerId}
                            selectedCustomer={customer}
                            setSelectedCustomer={setCustomer}
                        />
                    </div>

                    {/* Products */}
                    <div className="flex flex-col gap-1">
                        <h2 className="font-semibold">Select a Product</h2>

                        <Products
                            selectedProductId={selectedProductId}
                            setSelectedProductId={setSelectedProductId}
                            selectedProducts={product}
                            setSelectedProducts={setProduct}
                            quantity={quantity}
                            setQuantity={setQuantity}
                        />
                    </div>

                    <div className="lg:col-span-2 flex justify-end">
                        <button
                            onClick={evaluate}
                            disabled={loading || !customer || !product || !quantity}
                            className={`bg-blue-700 hover:bg-blue-600 w-35 h-9 text-sm rounded font-bold text-white
                                flex items-center justify-center transition-all duration-200 ease-in-out tracking-wider
                                ${loading || !customer || !product || !quantity
                                    ? "opacity-50 cursor-not-allowed"
                                    : "opacity-100 cursor-pointer hover:-translate-y-[1px] active:scale-89"
                                }`
                            }
                        >
                            {loading ? "Evaluating..." : "Evaluate"}
                        </button>
                    </div>
                </div>

                {/* Result Display */}
                {result && (
                    <div className="mt-4 border-t border-black/20 pt-4">
                        <div className="flex flex-col gap-1 bg-gradient-to-br from-blue-100 to-green-200 p-4 rounded-md">
                            <div className="mb-4">
                                <span className="text-sm font-bold">Applied:</span>
                                {result.applied.map((r, idx) => (
                                    <div key={idx} className="w-full xl:max-w-[70%] grid grid-cols-4 gap-5 items-center">
                                        <p className="col-span-1">-ruleId: {r.ruleId}</p>
                                        <p className="col-span-1">discount: {r.discount}</p>
                                        <p className="col-span-2">ruleName: "{r.ruleName}"</p>
                                    </div>
                                ))}
                            </div>

                            <p className="text-green-600 font-bold">
                                Total Discount: {result.totalDiscount}
                            </p>
                            <p className="text-blue-600 font-bold">
                                Final Price: {result.finalLineTotal}
                            </p>
                        </div>
                    </div>

                )}
            </div>
        </div>
    );
}
