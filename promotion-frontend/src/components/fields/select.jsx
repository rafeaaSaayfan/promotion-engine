export default function Select({ placeholder, value, options, onChange }) {
    return (
        <select
            className="border border-black/20 rounded-md bg-gray-100 py-1 px-2 cursor-pointer focus:outline focus:outline-blue-700 w-full"
            value={value}
            onChange={onChange}
        >
            {placeholder && <option value="" disabled>{placeholder}</option>}
            
            {options.map((option) => (
                <option key={option.value} value={option.value}>
                    {option.label}
                </option>
            ))}
        </select>
    );
}
