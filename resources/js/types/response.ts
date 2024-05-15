import {Item} from "./item";

export interface GenericResponse {
    message?: string;
    result?: number;
    data?: [];
}

export interface PaginatedResponse {
    current_page: number;
    next_page_url?: string;
    prev_page_url?: string;
    records: Item[];
    total: number;
    message?: string;
}
