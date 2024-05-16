import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head} from '@inertiajs/react';
import {PageProps} from '@/types';
import {ChangeEvent, ChangeEventHandler, useEffect, useState} from "react";
import {PaginatedResponse} from "../types/response";
import DataItem from "../Components/DataItem";

export default function Records({auth}: PageProps) {

    const [response, setResponse] = useState<PaginatedResponse>();
    const [page, setPage] = useState<number>(1);
    const [query, setQuery] = useState<string>('');
    const [order, setOrder] = useState<string>('');

    const loadRecords = async () => {
        try {
            const params = new URLSearchParams();
            params.append("page", page.toString());

            if (query) {
                params.append("query", query);
            }

            if (order) {
                params.append("order", order);
            }

            const response = await window.axios.get('/v1/operation', {params});
            setResponse(response.data);
        } catch (error: any) {
            alert(error.response.data.message);
        }
    }

    useEffect(() => {
        loadRecords()
    }, [page, order]);

    const changePage = (change: string) => {
        if (change === 'next' && response?.next_page_url) {
            setPage(page => page + 1);
        }

        if (change === 'prev' && response?.prev_page_url) {
            setPage(page => page - 1);
        }
    }

    const sortByHandler: ChangeEventHandler = async (e: ChangeEvent<HTMLSelectElement>) => {
        setOrder(e.target.value);
        setPage(1);
    }

    const filterHandler = async () => {
        if (page !== 1) {
            setPage(1);
        } else {
            await loadRecords();
        }
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2
                className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Operations</h2>}
        >
            <Head title="Operations"/>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                        <div className="w-full bg-white">
                            <div className="min-h-screen">

                                <div className="flex justify-between">

                                    <form className="w-full max-w-md">
                                        <div className="md:flex md:items-center mb-6 pt-3">
                                            <div className="md:w-2/3">
                                                <input
                                                    className="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white ml-2"
                                                    id="inline-full-name"
                                                    type="text"
                                                    placeholder="Query result"
                                                    value={query}
                                                    onChange={(e) => setQuery(e.target.value)}
                                                />
                                            </div>
                                            <div className="md:w-1/3">
                                                <button
                                                    onClick={() => filterHandler()}
                                                    className="shadow bg-green-700 hover:bg-green-800 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 ml-2"
                                                    type="button">
                                                    Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <form className="w-full max-w-sm">
                                        <div className="md:flex md:items-center mb-6 pt-3 pe-3">
                                            <div className="md:w-1/3">
                                                <label
                                                    className="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4"
                                                    htmlFor="inline-full-name">
                                                    Sort By:
                                                </label>
                                            </div>
                                            <div className="md:w-2/3">
                                                <select
                                                    onChange={sortByHandler}
                                                    value={order}
                                                    className="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                                >
                                                    <option value="">Select an option</option>
                                                    <option value="result">Result</option>
                                                    <option value="date">Date</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                                <div className="overflow-x-auto">
                                    <table className="min-w-full bg-white shadow-md rounded-xl">
                                        <thead>
                                        <tr className="bg-blue-gray-100 text-gray-700">
                                            <th className="py-3 px-4 text-left">Operation</th>
                                            <th className="py-3 px-4 text-left">Result</th>
                                            <th className="py-3 px-4 text-left">Date</th>
                                            <th className="py-3 px-4 text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody className="text-blue-gray-900">
                                        {
                                            response?.records.map((record) => (
                                                <DataItem
                                                    key={record.id}
                                                    record={record}
                                                    loadRecords={loadRecords}
                                                />
                                            ))
                                        }
                                        {
                                            !response?.records.length && (
                                                <tr>
                                                    <td colSpan={4} className="text-center text-red-600">There are no
                                                        records to display.
                                                    </td>
                                                </tr>
                                            )
                                        }
                                        </tbody>
                                    </table>
                                    <div className="w-full pt-5 px-4 mb-8 mx-auto ">
                                        <div className="text-sm text-gray-800 py-1 text-center">

                                            <nav aria-label="Page navigation example">
                                                <ul className="inline-flex -space-x-px text-base h-10">
                                                    <li>
                                                        <button
                                                            onClick={() => changePage('prev')}
                                                            className="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button
                                                            disabled
                                                            className="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Page: {page}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button
                                                            onClick={() => changePage('next')}
                                                            className="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                                        >Next
                                                        </button>
                                                    </li>
                                                </ul>
                                            </nav>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
