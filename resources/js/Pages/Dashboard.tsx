import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head} from '@inertiajs/react';
import {PageProps} from '@/types';
import {FormEventHandler, useState} from "react";
import {Operations} from "../types/operations";
import {GenericResponse} from "../types/response";

export default function Dashboard({auth}: PageProps) {

    const [firstValue, setFirstValue] = useState<number>(0);
    const [secondValue, setSecondValue] = useState<number>(0);
    const [operationType, setOperationType] = useState<Operations>(Operations.ADDITION);
    const [response, setResponse] = useState<GenericResponse>({result: undefined});

    const submitHandler: FormEventHandler = async (e) => {
        e.preventDefault();
        try {
            const response = await window.axios.post<GenericResponse>('/v1/operation', {
                operationType,
                firstValue,
                secondValue
            });
            console.warn(response);
            setResponse(response.data);
        } catch (error: any) {
            alert(error.response.data.message);
            setResponse({result: undefined});
        }
    }

    const showFirstValue: boolean = ([Operations.ADDITION,
            Operations.SUBTRACTION,
            Operations.MULTIPLICATION,
            Operations.DIVISION,
            Operations.SQUARE_ROOT
        ] as unknown as Operations).includes(operationType);

    const showSecondValue: boolean = ([Operations.ADDITION,
            Operations.SUBTRACTION,
            Operations.MULTIPLICATION,
            Operations.DIVISION
        ] as unknown as Operations).includes(operationType);

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2
                className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Operations</h2>}
        >
            <Head title="Dashboard"/>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-center">

                        <div className="w-full max-w-sm">
                            <form
                                className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                                onSubmit={submitHandler}
                            >
                                <div className="mb-4">
                                    <label
                                        className="block text-gray-700 text-sm font-bold mb-2"
                                        htmlFor="grid-state">
                                        Type
                                    </label>
                                    <div className="relative">
                                        <select
                                            className="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                            id="grid-state"
                                            value={operationType}
                                            onChange={(e) => setOperationType(e.target.value as Operations)}
                                        >
                                            {Object.keys(Operations).map((operation, index) => (
                                                <option key={index}>{operation}</option>
                                            ))}
                                        </select>
                                    </div>
                                </div>
                                {showFirstValue &&
                                <div className="mb-4">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Value
                                    </label>
                                    <input
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        id="username"
                                        type="number"
                                        value={firstValue}
                                        onChange={(e) => setFirstValue(+e.target.value)}/>
                                </div>
                                }
                                {showSecondValue &&
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="password">
                                        Second Value
                                    </label>
                                    <input
                                        className="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                                        id="password"
                                        type="number"
                                        value={secondValue}
                                        onChange={(e) => setSecondValue(+e.target.value)}/>
                                </div>
                                }
                                <div className="flex items-center justify-between">
                                    <button
                                        className="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full uppercase"
                                        type="submit">
                                        Get result
                                    </button>
                                </div>
                            </form>

                            <div className="bg-white shadow-md rounded px-8 p-6 mb-4">
                                <p className="text-xl">{response?.result !== undefined ? `The result is ${response.result}` : ''}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
