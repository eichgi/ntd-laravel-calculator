import {Item} from "../types/item";
import DangerButton from "./DangerButton";
import {GenericResponse} from "../types/response";

export interface Props {
    record: Item;
    loadRecords: () => void
}

export default function DataItem({record, loadRecords}: Props) {

    const deleteHandler = async (id: number) => {
        try {
            await window.axios.delete<GenericResponse>('v1/operation/' + id);
            loadRecords();
        } catch (error) {
            console.warn(error);
        }
    };

    return (
        <tr className="border-b border-blue-gray-200">
            <td className="py-3 px-4">{record.type}</td>
            <td className="py-3 px-4">{record.result}</td>
            <td className="py-3 px-4">{record.date}</td>
            <td className="py-3 px-4">
                <DangerButton onClick={() => deleteHandler(record.id)}>Delete</DangerButton>
            </td>
        </tr>
    );
}
