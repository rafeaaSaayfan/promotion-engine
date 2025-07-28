export default function Input({ placeholder, type, value, ...rest }) {

    return (
        <input
            className="border border-black/20 rounded-md bg-gray-100 py-1 px-2 cursor-pointer focus:outline focus:outline-blue-700 w-full"
            placeholder={placeholder}
            type={type}
            value={value || ''}
            { ...rest }
        />
    )
}
